# KDS – Disaster Management Decision Support System | Afet Yönetimi Karar Destek Sistemi

**Languages / Diller:** [English](#english) · [Türkçe](#türkçe)

---

<a id="english"></a>

# English

A decision support system that collects needs from disaster areas (water, food, tents, medicine), ranks regions by a **KDS score**, and automatically recommends / assigns the most suitable relief team.

The project consists of two services:

- **Laravel 12 (PHP)** – web interface, admin panel, authentication, CRUD operations
- **FastAPI (Python)** – analytics, scoring, map data and the team assignment algorithm

## Features

- Public **need report form** for citizens (home page)
- Admin panel (CRUD): resources, needs, regions, victims, relief teams, distributions, reports, notifications
- **Dashboard**: total need, critical region count, distribution summary, 7-day trend, need breakdown, team status, top 5 regions by need
- **Map** showing region need scores and suggested team types
- **Automatic team assignment**: picks the team type from the region's needs, finds the nearest team (Haversine) and estimates arrival time (ETA)
- Authentication (Laravel Breeze) and profile management

## Architecture

```
Browser ──► Laravel (127.0.0.1:8000) ──► MySQL (afet)
   │                │                        ▲
   │                └─► FastAPI (127.0.0.1:8001) ─┘
   └──────────────────► FastAPI (map and team assignment requests)
```

## Tech Stack

| Layer | Technology |
| --- | --- |
| Backend | PHP ^8.2, Laravel 12, Laravel Breeze, Laravel Sanctum |
| Analytics service | Python 3.12, FastAPI, Uvicorn, mysql-connector-python, python-dotenv |
| Database | MySQL |
| Frontend | Blade, Bootstrap-based admin theme, Tailwind CSS 3 + Alpine.js (auth pages), Vite |

## Requirements

- PHP 8.2+ and Composer
- Node.js 18+ and npm
- Python 3.10+ (developed with 3.12)
- MySQL 8+

## Installation

### 1. Clone the repository

```bash
git clone <repo-url>
cd KDSProject
```

### 2. Laravel setup

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Create the database in MySQL:

```sql
CREATE DATABASE afet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

In `.env`, uncomment the database lines and fill in your own credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=afet
DB_USERNAME=root
DB_PASSWORD=
```

Create the tables and load the sample data:

```bash
php artisan migrate --seed
```

> ⚠️ `DatabaseSeeder` **truncates** the related tables when it runs. Do not run it against a database that contains data you want to keep.

### 3. Python API setup

```bash
cd python-api
python -m venv venv

# Windows
venv\Scripts\activate
# Linux / macOS
source venv/bin/activate

pip install -r requirements.txt
```

The Python API reads the `.env` file in the project root:

| Variable | Default |
| --- | --- |
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_USER` | `root` |
| `DB_PASSWORD` | empty |
| `DB_NAME` | `afet` |

> Note: Laravel uses `DB_USERNAME` / `DB_DATABASE`, while the Python API uses `DB_USER` / `DB_NAME`. If your username is not `root` or your database is not named `afet`, add `DB_USER` and `DB_NAME` to your `.env` as well.

## Running the Project

Open three terminals:

```bash
# 1) Python API (must run on port 8001)
cd python-api
uvicorn main:app --reload --port 8001

# 2) Laravel
php artisan serve

# 3) Frontend (development) – or for production: npm run build
npm run dev
```

- Application: http://127.0.0.1:8000
- Python API docs (Swagger): http://127.0.0.1:8001/docs

### First admin user

The passwords of the seeded users are unknown, so create your own admin:

```bash
php artisan tinker
>>> App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'ChooseAStrongPassword123', 'rol' => 'Admin']);
```

## Pages and Routes

| Route | Description | Access |
| --- | --- | --- |
| `/` | Home page and need report form | Public |
| `POST /need-report` | Submit a need report | Public |
| `/dashboard` | Analytics dashboard and map | Authenticated users |
| `/admin/{resources, needs, regions, victims, relief-teams, distributions, reports, notifications}` | Admin panel CRUD | Authenticated users |
| `/profile` | Edit profile | Authenticated users |

## Python API Endpoints

| Method | Path | Description |
| --- | --- | --- |
| GET | `/` | Service status |
| GET | `/analytics/summary` | Total water, food, tents, medicine and number of people |
| GET | `/analytics/regions/top` | Top 5 regions by KDS score |
| GET | `/analytics/regions/critical-count` | Number of critical regions |
| GET | `/analytics/needs/distribution` | Breakdown by need type |
| GET | `/analytics/needs/trend/7days` | Last 7 days compared with the previous 7 days |
| GET | `/analytics/distribution/summary` | Distributed amount and pending need |
| GET | `/analytics/teams/status-distribution` | Team counts by status |
| GET | `/analytics/map` | Regions, scores and suggested team types for the map |
| POST | `/analytics/assign` | Automatically assign a team to a region (`{"bolge_id": 1, "priority": 1}`) |

## KDS Score and Prioritization

Each region's need score is a weighted sum:

```
KDS score = 0.20 × water (litres) + 0.15 × food packages + 0.30 × tents + 0.35 × medicine
```

- Regions with a score of **100 or more** are considered *critical*.
- Priority: `≥ 120` → 1 (Critical), `≥ 90` → 2 (High), `≥ 60` → 3 (Medium), otherwise → 4 (Low)
- Suggested team types: medicine ≥ 50 → **Saglik** (health); food + tents ≥ 100 → **Lojistik** (logistics); score ≥ 120 → **AramaKurtarma** (search & rescue); score ≥ 90 → **Psikososyal** (psychosocial support)

## Automatic Team Assignment Algorithm

1. The target team type is chosen from the region's needs: medicine > 50 → `Saglik`, tents > 20 → `Lojistik`, otherwise `AramaKurtarma`.
2. Team selection runs in tiers:
   1. Ready, correct type, known location and not assigned elsewhere → the **nearest** one is picked (Haversine)
   2. Ready team of the correct type (no location data)
   3. Emergency: teams with status `Dinleniyor` (resting) are included
   4. Last resort: the team type requirement is relaxed
3. ETA is computed assuming a travel speed of 50 km/h.
4. An assignment record is created, the team is marked as en route, and the decision reason is logged.

## Project Structure

```
KDSProject/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/            # Admin panel controllers
│   │   ├── Api/              # Bridge to the Python API
│   │   └── Auth/             # Breeze authentication
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/              # CSV-based sample data
├── python-api/               # FastAPI analytics service
│   ├── main.py
│   ├── analytics.py
│   └── database.py
├── resources/views/          # Blade templates (admin, Home, auth)
├── routes/
└── tests/
```

## Tests

```bash
php artisan test
```

## Roadmap

- [ ] Role-based authorization (Admin / User)
- [ ] Authentication and a restricted CORS policy for the Python API
- [ ] Make the Python API URL configurable through `.env`
- [ ] Project-specific automated tests

## Team

- Ragad
- fatemalzahraa
- Joud Khanji
- rojaahmed
- Tasnim Al Abhas

## License

No license has been chosen yet. Consider adding one (e.g. MIT) before publishing.

---

<a id="türkçe"></a>

# Türkçe

Afet bölgelerindeki ihtiyaçları (su, gıda, çadır, ilaç) toplayan, bölgeleri **KDS puanı** ile önceliklendiren ve en uygun yardım ekibini otomatik öneren / atayan bir karar destek sistemidir.

Proje iki servisten oluşur:

- **Laravel 12 (PHP)** – web arayüzü, yönetim paneli, kimlik doğrulama, CRUD işlemleri
- **FastAPI (Python)** – analiz, puanlama, harita verisi ve ekip atama algoritması

## Özellikler

- Vatandaşlar için herkese açık **ihtiyaç bildirim formu** (ana sayfa)
- Yönetim paneli (CRUD): kaynaklar, ihtiyaçlar, bölgeler, afetzedeler, yardım ekipleri, dağıtımlar, raporlar, bildirimler
- **Dashboard**: toplam ihtiyaç, kritik bölge sayısı, dağıtım özeti, 7 günlük trend, ihtiyaç dağılımı, ekip durumu, en çok ihtiyaç duyan 5 bölge
- **Harita** üzerinde bölgelerin ihtiyaç puanları ve önerilen ekip türleri
- **Otomatik ekip atama**: ihtiyaca göre ekip türü seçimi, en yakın ekibin bulunması (Haversine) ve tahmini varış süresi (ETA)
- Kimlik doğrulama (Laravel Breeze) ve profil yönetimi

## Mimari

```
Tarayıcı ──► Laravel (127.0.0.1:8000) ──► MySQL (afet)
   │                │                        ▲
   │                └─► FastAPI (127.0.0.1:8001) ─┘
   └──────────────────► FastAPI (harita ve ekip atama istekleri)
```

## Teknolojiler

| Katman | Teknoloji |
| --- | --- |
| Backend | PHP ^8.2, Laravel 12, Laravel Breeze, Laravel Sanctum |
| Analiz servisi | Python 3.12, FastAPI, Uvicorn, mysql-connector-python, python-dotenv |
| Veritabanı | MySQL |
| Frontend | Blade, Bootstrap tabanlı admin teması, Tailwind CSS 3 + Alpine.js (auth sayfaları), Vite |

## Gereksinimler

- PHP 8.2+ ve Composer
- Node.js 18+ ve npm
- Python 3.10+ (proje 3.12 ile geliştirildi)
- MySQL 8+

## Kurulum

### 1. Depoyu klonlayın

```bash
git clone <repo-url>
cd KDSProject
```

### 2. Laravel tarafı

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

MySQL'de veritabanını oluşturun:

```sql
CREATE DATABASE afet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

`.env` dosyasında veritabanı satırlarının yorumunu kaldırıp kendi bilgilerinizi girin:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=afet
DB_USERNAME=root
DB_PASSWORD=
```

Tabloları oluşturup örnek verileri yükleyin:

```bash
php artisan migrate --seed
```

> ⚠️ `DatabaseSeeder` çalışırken ilgili tabloları **temizler (truncate)**. Dolu bir veritabanında çalıştırmayın.

### 3. Python API tarafı

```bash
cd python-api
python -m venv venv

# Windows
venv\Scripts\activate
# Linux / macOS
source venv/bin/activate

pip install -r requirements.txt
```

Python API, kök dizindeki `.env` dosyasını okur:

| Değişken | Varsayılan |
| --- | --- |
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_USER` | `root` |
| `DB_PASSWORD` | boş |
| `DB_NAME` | `afet` |

> Not: Laravel `DB_USERNAME` / `DB_DATABASE` kullanırken Python API `DB_USER` / `DB_NAME` kullanır. Kullanıcı adınız `root`, veritabanı adınız `afet` değilse `.env` dosyasına `DB_USER` ve `DB_NAME` satırlarını da ekleyin.

## Çalıştırma

Üç ayrı terminal açın:

```bash
# 1) Python API (port 8001 olmalı)
cd python-api
uvicorn main:app --reload --port 8001

# 2) Laravel
php artisan serve

# 3) Frontend (geliştirme) – veya üretim için: npm run build
npm run dev
```

- Uygulama: http://127.0.0.1:8000
- Python API dokümantasyonu (Swagger): http://127.0.0.1:8001/docs

### İlk yönetici kullanıcı

Seed ile gelen kullanıcıların şifreleri bilinmediği için kendi yöneticinizi oluşturun:

```bash
php artisan tinker
>>> App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'GucluBirSifre123', 'rol' => 'Admin']);
```

## Sayfalar ve Rotalar

| Rota | Açıklama | Erişim |
| --- | --- | --- |
| `/` | Ana sayfa ve ihtiyaç bildirim formu | Herkes |
| `POST /need-report` | İhtiyaç bildirimi gönderme | Herkes |
| `/dashboard` | Analiz paneli ve harita | Giriş yapmış kullanıcı |
| `/admin/{resources, needs, regions, victims, relief-teams, distributions, reports, notifications}` | Yönetim paneli CRUD | Giriş yapmış kullanıcı |
| `/profile` | Profil düzenleme | Giriş yapmış kullanıcı |

## Python API Uç Noktaları

| Metot | Yol | Açıklama |
| --- | --- | --- |
| GET | `/` | Servis durumu |
| GET | `/analytics/summary` | Toplam su, gıda, çadır, ilaç ve kişi sayısı |
| GET | `/analytics/regions/top` | KDS puanına göre en yüksek 5 bölge |
| GET | `/analytics/regions/critical-count` | Kritik bölge sayısı |
| GET | `/analytics/needs/distribution` | İhtiyaç türlerine göre dağılım |
| GET | `/analytics/needs/trend/7days` | Son 7 gün ile önceki 7 günün karşılaştırması |
| GET | `/analytics/distribution/summary` | Dağıtılan miktar ve bekleyen ihtiyaç |
| GET | `/analytics/teams/status-distribution` | Ekip durumlarına göre sayılar |
| GET | `/analytics/map` | Harita için bölge, puan ve önerilen ekip türleri |
| POST | `/analytics/assign` | Bölgeye otomatik ekip atama (`{"bolge_id": 1, "priority": 1}`) |

## KDS Puanı ve Önceliklendirme

Her bölge için ihtiyaç puanı ağırlıklı toplamla hesaplanır:

```
KDS puanı = 0.20 × su (litre) + 0.15 × gıda paketi + 0.30 × çadır + 0.35 × ilaç
```

- Puanı **100 ve üzeri** olan bölgeler *kritik* sayılır.
- Öncelik: `≥ 120` → 1 (Kritik), `≥ 90` → 2 (Yüksek), `≥ 60` → 3 (Orta), diğerleri → 4 (Düşük)
- Önerilen ekip türleri: ilaç ≥ 50 → **Saglik**; gıda + çadır ≥ 100 → **Lojistik**; puan ≥ 120 → **AramaKurtarma**; puan ≥ 90 → **Psikososyal**

## Otomatik Ekip Atama Algoritması

1. Bölgenin ihtiyacına göre hedef ekip türü belirlenir: ilaç > 50 → `Saglik`, çadır > 20 → `Lojistik`, aksi halde `AramaKurtarma`.
2. Ekip seçimi kademeli yapılır:
   1. Hazır, doğru türde, konumu bilinen ve boşta ekip → **en yakın** olan seçilir (Haversine)
   2. Hazır ve doğru türde ekip (konum bilgisi olmadan)
   3. Acil durum: `Dinleniyor` durumundaki ekipler de dahil edilir
   4. Son çare: ekip türü esnetilir
3. ETA, mesafenin 50 km/sa hızla gidilmesine göre hesaplanır.
4. Atama kaydı oluşturulur, ekip yola çıkmış olarak işaretlenir ve karar gerekçesi loglanır.

## Proje Yapısı

```
KDSProject/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/            # Yönetim paneli controller'ları
│   │   ├── Api/              # Python API köprüsü
│   │   └── Auth/             # Breeze kimlik doğrulama
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/              # CSV tabanlı örnek veriler
├── python-api/               # FastAPI analiz servisi
│   ├── main.py
│   ├── analytics.py
│   └── database.py
├── resources/views/          # Blade şablonları (admin, Home, auth)
├── routes/
└── tests/
```

## Testler

```bash
php artisan test
```

## Yol Haritası

- [ ] Rol tabanlı yetkilendirme (Admin / Kullanıcı)
- [ ] Python API için kimlik doğrulama ve CORS kısıtlaması
- [ ] Python API adresinin `.env` üzerinden yapılandırılması
- [ ] Uygulamaya özel otomatik testler

## Ekip

- Ragad
- fatemalzahraa
- Joud Khanji
- rojaahmed
- Tasnim Al Abhas

## Lisans

Lisans henüz belirlenmemiştir. Yayınlamadan önce bir lisans (ör. MIT) eklemeniz önerilir.
