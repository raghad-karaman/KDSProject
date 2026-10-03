from database import get_db
from datetime import datetime
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import math

app = FastAPI()

class AssignRequest(BaseModel):
    bolge_id: int
    priority: int

def calculate_eta(distance_km, speed_kmh=50):
    return int((distance_km / speed_kmh) * 60)

def haversine(lat1, lon1, lat2, lon2):
    R = 6371
    phi1, phi2 = math.radians(lat1), math.radians(lat2)
    dphi = math.radians(lat2 - lat1)
    dlambda = math.radians(lon2 - lon1)

    a = math.sin(dphi / 2) ** 2 + \
        math.cos(phi1) * math.cos(phi2) * math.sin(dlambda / 2) ** 2
    return 2 * R * math.atan2(math.sqrt(a), math.sqrt(1 - a))


@app.post("/analytics/assign")
def assign_team(req: AssignRequest):
    db = get_db()
    cursor = db.cursor(dictionary=True)

    try:
        # 1️⃣ Bölge ihtiyacını al
        cursor.execute("""
            SELECT
                r.latitude, r.longitude,
                COALESCE(SUM(n.ilac_adet), 0) AS ilac,
                COALESCE(SUM(n.cadir), 0) AS cadir,
                COALESCE(SUM(n.su_litre + n.gida_paketi), 0) AS yasam
            FROM regions r
            LEFT JOIN needs n ON n.bolge_id = r.id
            WHERE r.id = %s
            GROUP BY r.id, r.latitude, r.longitude
        """, (req.bolge_id,))
        region = cursor.fetchone()

        if not region:
            raise HTTPException(404, "Bölge bulunamadı")

        # 2️⃣ Hedef ekip türü belirle
        if region["ilac"] > 50:
            hedef_tur = "Saglik"
            neden = "Bölgede ilaç ihtiyacı yüksek"
        elif region["cadir"] > 20:
            hedef_tur = "Lojistik"
            neden = "Barınma ihtiyacı yüksek"
        else:
            hedef_tur = "AramaKurtarma"
            neden = "Genel kurtarma ihtiyacı"

        # 3️⃣ KADEMELİ EKİP SEÇİMİ
        kademe = 1

        # KADEME 1️⃣ — Hazır + Tür + Konum
        cursor.execute("""
            SELECT id, ekip_adi, latitude, longitude
            FROM relief_teams
            WHERE
                durum = 'Hazır'
                AND ekip_turu = %s
                AND bolge_id IS NULL
                AND latitude IS NOT NULL
                AND longitude IS NOT NULL
        """, (hedef_tur,))
        teams = cursor.fetchall()

        # KADEME 2️⃣ — Hazır + Tür (konum yok)
        if not teams:
            kademe = 2
            cursor.execute("""
                SELECT id, ekip_adi, latitude, longitude
                FROM relief_teams
                WHERE
                    durum = 'Hazır'
                    AND ekip_turu = %s
                    AND bolge_id IS NULL
            """, (hedef_tur,))
            teams = cursor.fetchall()

        # KADEME 3️⃣ — ACİL: Dinleniyor ekipler dahil
        if not teams:
            kademe = 3
            cursor.execute("""
                SELECT id, ekip_adi, latitude, longitude
                FROM relief_teams
                WHERE
                    durum IN ('Hazır', 'Dinleniyor')
                    AND ekip_turu = %s
                    AND bolge_id IS NULL
            """, (hedef_tur,))
            teams = cursor.fetchall()

        # KADEME 4️⃣ — SON ÇARE: Tür esnet
        if not teams:
            kademe = 4
            cursor.execute("""
                SELECT id, ekip_adi, latitude, longitude
                FROM relief_teams
                WHERE
                    durum IN ('Hazır', 'Dinleniyor')
                    AND bolge_id IS NULL
            """)
            teams = cursor.fetchall()

        if not teams:
            raise HTTPException(400, "Gerçekten müsait ekip yok")

        # 4️⃣ En uygun ekip seçimi
        if kademe == 1 and region["latitude"] is not None and region["longitude"] is not None:
            secilen = min(
                teams,
                key=lambda t: haversine(
                    region["latitude"], region["longitude"],
                    t["latitude"], t["longitude"]
                )
            )
            distance_km = haversine(
                region["latitude"], region["longitude"],
                secilen["latitude"], secilen["longitude"]
            )
            neden_detay = "En yakın uygun ekip seçildi"
        else:
            secilen = teams[0]
            distance_km = None
            if kademe == 2:
                neden_detay = "Konum bilgisi olmayan hazır ekip seçildi"
            elif kademe == 3:
                neden_detay = "Dinleniyor ekip acil durum için göreve alındı"
            else:
                neden_detay = "Acil durum: ekip türü esnetildi"

        # 5️⃣ ETA hesapla
        eta_minutes = calculate_eta(distance_km) if distance_km else None
        now = datetime.now()

        # 6️⃣ team_assignments → YOLDA
        cursor.execute("""
            INSERT INTO team_assignments
            (team_id, bolge_id, priority, status,
             assigned_at, started_at, eta_minutes,
             created_at, updated_at)
            VALUES (%s, %s, %s, 'YOLDA',
                    %s, %s, %s,
                    %s, %s)
        """, (
            secilen["id"],
            req.bolge_id,
            req.priority,
            now,
            now,
            eta_minutes,
            now,
            now
        ))

        # 7️⃣ Ekibi YOLDA yap
        cursor.execute("""
            UPDATE relief_teams
            SET durum = 'Yolda',
                bolge_id = %s,
                updated_at = %s
            WHERE id = %s
        """, (req.bolge_id, now, secilen["id"]))

        # 8️⃣ Karar logu
        cursor.execute("""
            INSERT INTO kds_assignment_logs
            (bolge_id, team_id, decision_reason)
            VALUES (%s, %s, %s)
        """, (
            req.bolge_id,
            secilen["id"],
            f"{hedef_tur} ekibi seçildi. {neden}. "
            f"{neden_detay}. Kademe {kademe}. "
            f"ETA: {eta_minutes if eta_minutes else 'Hesaplanamadı'}"
        ))

        db.commit()

        return {
            "message": "Ekip yola çıktı",
            "team": secilen["ekip_adi"],
            "ekip_turu": hedef_tur,
            "kademe": kademe,
            "eta_minutes": eta_minutes,
            "reason": f"{neden}. {neden_detay}."
        }

    except HTTPException:
        db.rollback()
        raise
    except Exception as e:
        db.rollback()
        raise HTTPException(500, str(e))
    finally:
        cursor.close()
        db.close()


def get_summary():
    db = get_db()
    cursor = db.cursor(dictionary=True)
    cursor.execute("""
        SELECT
            COALESCE(SUM(su_litre), 0) AS total_water,
            COALESCE(SUM(gida_paketi), 0) AS total_food,
            COALESCE(SUM(cadir), 0) AS total_tent,
            COALESCE(SUM(ilac_adet), 0) AS total_medicine,
            COUNT(DISTINCT victim_id) AS total_people
        FROM needs
    """)
    result = cursor.fetchone()
    cursor.close()
    db.close()
    return result


def suggest_teams(region: dict) -> list[str]:
    teams = []

    medicine = region.get("medicine") or region.get("ilac") or 0
    food = region.get("food") or 0
    tent = region.get("tent") or region.get("cadir") or 0
    kds_score = region.get("kds_score") or 0

    if medicine >= 50:
        teams.append("Saglik")

    if (food + tent) >= 100:
        teams.append("Lojistik")

    if kds_score >= 120:
        teams.append("AramaKurtarma")

    if kds_score >= 90:
        teams.append("Psikososyal")

    return teams


def get_top_regions():
    db = get_db()
    cursor = db.cursor(dictionary=True)
    cursor.execute("""
        SELECT
            r.id,
            r.ad AS region_name,
            r.il,
            COALESCE(
                0.20 * SUM(n.su_litre) +
                0.15 * SUM(n.gida_paketi) +
                0.30 * SUM(n.cadir) +
                0.35 * SUM(n.ilac_adet), 0
            ) AS kds_score
        FROM regions r
        LEFT JOIN needs n ON r.id = n.bolge_id
        GROUP BY r.id, r.ad, r.il
        ORDER BY kds_score DESC
        LIMIT 5
    """)
    result = cursor.fetchall()
    cursor.close()
    db.close()
    return result


def get_needs_distribution():
    db = get_db()
    cursor = db.cursor(dictionary=True)
    cursor.execute("""
        SELECT
            COALESCE(SUM(su_litre), 0) AS water,
            COALESCE(SUM(gida_paketi), 0) AS food,
            COALESCE(SUM(cadir), 0) AS tent,
            COALESCE(SUM(ilac_adet), 0) AS medicine
        FROM needs
    """)
    result = cursor.fetchone()
    cursor.close()
    db.close()
    return result


def get_map_data():
    """
    Harita için bölgeleri ve ihtiyaç puanlarını çeker.
    JOIN yerine LEFT JOIN kullanılarak ihtiyacı olmayan bölgelerin de haritada listelenmesi sağlandı.
    """
    db = get_db()
    cursor = db.cursor(dictionary=True)

    cursor.execute("""
        SELECT
            r.id,
            r.ad,
            r.il,
            r.ilce,
            r.latitude,
            r.longitude,
            COALESCE(SUM(n.su_litre), 0) AS water,
            COALESCE(SUM(n.gida_paketi), 0) AS food,
            COALESCE(SUM(n.cadir), 0) AS tent,
            COALESCE(SUM(n.ilac_adet), 0) AS medicine,
            COALESCE(
                SUM(
                    0.20 * n.su_litre +
                    0.15 * n.gida_paketi +
                    0.30 * n.cadir +
                    0.35 * n.ilac_adet
                ), 0
            ) AS kds_score
        FROM regions r
        LEFT JOIN needs n ON r.id = n.bolge_id
        GROUP BY r.id, r.ad, r.il, r.ilce, r.latitude, r.longitude
    """)

    result = cursor.fetchall()
    cursor.close()
    db.close()

    for r in result:
        r["suggested_teams"] = suggest_teams(r)

    return result


def get_critical_region_count():
    db = get_db()
    cursor = db.cursor(dictionary=True)
    cursor.execute("""
        SELECT COUNT(*) AS critical_count
        FROM (
            SELECT
                r.id,
                COALESCE(
                    0.20 * SUM(n.su_litre) +
                    0.15 * SUM(n.gida_paketi) +
                    0.30 * SUM(n.cadir) +
                    0.35 * SUM(n.ilac_adet), 0
                ) AS kds_score
            FROM regions r
            LEFT JOIN needs n ON r.id = n.bolge_id
            GROUP BY r.id
            HAVING kds_score >= 100
        ) t
    """)
    result = cursor.fetchone()
    cursor.close()
    db.close()
    return result


def get_need_trend_7_days():
    """
    Son 7 gün ve önceki 7 günün ihtiyaç trendlerini karşılaştırır.
    Frontend ile %100 kalıcı ve esnek uyum için alternatif key'ler eklenmiştir.
    """
    db = get_db()
    cursor = db.cursor(dictionary=True)

    cursor.execute("""
        SELECT COALESCE(SUM(su_litre + gida_paketi + cadir + ilac_adet), 0) AS total
        FROM needs
        WHERE created_at >= NOW() - INTERVAL 7 DAY
    """)
    row_last_7 = cursor.fetchone()
    last_7 = int(row_last_7["total"]) if row_last_7 else 0

    cursor.execute("""
        SELECT COALESCE(SUM(su_litre + gida_paketi + cadir + ilac_adet), 0) AS total
        FROM needs
        WHERE created_at BETWEEN NOW() - INTERVAL 14 DAY AND NOW() - INTERVAL 7 DAY
    """)
    row_prev_7 = cursor.fetchone()
    prev_7 = int(row_prev_7["total"]) if row_prev_7 else 0

    cursor.close()
    db.close()

    if prev_7 == 0 and last_7 > 0:
        change = 100.0
    elif prev_7 > 0:
        change = ((last_7 - prev_7) / prev_7) * 100.0
    else:
        change = 0.0

    rounded_change = round(change, 1)

    return {
        "last_7_total": last_7,
        "total": last_7,
        "value": last_7,
        "change_percent": rounded_change,
        "change": rounded_change
    }

def get_team_status_distribution():
    db = get_db()
    cursor = db.cursor(dictionary=True)
    cursor.execute("""
        SELECT durum, COUNT(*) AS total
        FROM relief_teams
        GROUP BY durum
    """)
    result = cursor.fetchall()
    cursor.close()
    db.close()
    return result


def calculate_priority(kds_score: float) -> int:
    """
    1 = Kritik, 2 = Yüksek, 3 = Orta, 4 = Düşük
    """
    if kds_score >= 120:
        return 1
    elif kds_score >= 90:
        return 2
    elif kds_score >= 60:
        return 3
    return 4


def suggest_team_types(region: dict) -> list[str]:
    teams = []

    medicine = region.get("medicine") or region.get("ilac") or 0
    food = region.get("food") or 0
    tent = region.get("tent") or region.get("cadir") or 0
    kds_score = region.get("kds_score") or 0

    if medicine >= 50:
        teams.append("Saglik")

    if (food + tent) >= 100:
        teams.append("Lojistik")

    if kds_score >= 120:
        teams.append("AramaKurtarma")

    if kds_score >= 90:
        teams.append("Psikososyal")

    return list(set(teams))


def get_kds_recommendations():
    regions = get_map_data()

    for r in regions:
        r["priority"] = calculate_priority(r["kds_score"])
        r["suggested_teams"] = suggest_team_types(r)

    return regions


def create_team_assignment_suggestions():
    regions = get_kds_recommendations()
    db = get_db()
    cursor = db.cursor()

    for r in regions:
        for team_type in r["suggested_teams"]:
            cursor.execute("""
                INSERT INTO team_assignments
                (team_id, bolge_id, priority, status, created_at, updated_at)
                SELECT
                    t.id,
                    %s,
                    %s,
                    'SUGGESTED',
                    %s,
                    %s
                FROM relief_teams t
                WHERE t.ekip_turu = %s
                  AND t.durum = 'Hazır'
                LIMIT 1
            """, (
                r["id"],
                r["priority"],
                datetime.now(),
                datetime.now(),
                team_type
            ))

    db.commit()
    cursor.close()
    db.close()


def get_distribution_summary():
    db = get_db()
    cursor = db.cursor(dictionary=True)

    cursor.execute("""
        SELECT COALESCE(SUM(miktar), 0) AS total_distributed
        FROM distribution
    """)
    row_dist = cursor.fetchone()
    distributed = row_dist["total_distributed"] if row_dist else 0

    cursor.execute("""
        SELECT
            COALESCE(SUM(
                su_litre +
                gida_paketi +
                cadir +
                ilac_adet
            ), 0) AS total_needed
        FROM needs
    """)
    row_need = cursor.fetchone()
    needed = row_need["total_needed"] if row_need else 0

    cursor.close()
    db.close()

    return {
        "total_distributed": int(distributed),
        "pending_need": int(max(needed - distributed, 0))
    }