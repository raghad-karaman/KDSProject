@extends('admin.layouts.admin')

@section('title', 'Dashboard')
<style>
  #criticalMap {
    height: 420px;
    width: 100%;
    border-radius: 0 0 12px 12px;
  }
</style>
@push('styles')
<link
  rel="stylesheet"
  href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
@endpush

@section('content')

<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>


  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->


        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->


            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->


                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">

                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">John Doe</span>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-8 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Congratulations John! 🎉</h5>
                          <p class="mb-4">
                            You have done <span class="fw-bold">72%</span> more sales today. Check your new badge in
                            your profile.
                          </p>

                          <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="../assets/img/illustrations/man-with-laptop-light.png"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            <div class="col-lg-4 col-md-4 order-1">
    <div class="row">

        {{-- Toplam İhtiyaç --}}
        <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="../assets/img/icons/unicons/chart-success.png" class="rounded" />
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Toplam İhtiyaç</span>
<h3 class="card-title mb-2">
    {{ number_format($totalNeed) }}
</h3>
                    <small class="text-danger fw-semibold">
                        <i class="bx bx-up-arrow-alt"></i> Son 24 saatte +12
                    </small>
                </div>
            </div>
        </div>

        {{-- Kritik Bölge --}}
        <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="../assets/img/icons/unicons/wallet-info.png" class="rounded" />
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Kritik Bölge</span>
<h3 class="card-title text-nowrap mb-1">
    {{ $criticalRegionCount }}
</h3>
                    <small class="text-warning fw-semibold">
                        <i class="bx bx-error"></i> Acil müdahale gerekli
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>

                <!-- En Çok İhtiyaç Olan 5 Bölge -->
<div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
  <div class="card">
    <div class="row row-bordered g-0">
      <div class="col-md-12">
        <h5 class="card-header m-0 me-2 pb-3">
          En Çok İhtiyaç Olan 5 Bölge
        </h5>

        <!-- BAR CHART -->
        <div id="needRegionsChart" class="px-3 pb-4"></div>
      </div>
    </div>
  </div>
</div>
<!--/ En Çok İhtiyaç Olan 5 Bölge -->

                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                  <div class="row">
                    <div class="col-6 mb-4">
                     <div class="card">
    <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
                <img src="../assets/img/icons/unicons/paypal.png" alt="Toplam Dağıtım" class="rounded" />
            </div>

            <div class="dropdown">
                <button
                    class="btn p-0"
                    type="button"
                    id="cardOpt4"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                    <a class="dropdown-item" href="javascript:void(0);">Detayları Gör</a>
                </div>
            </div>
        </div>

        {{-- KPI Başlık --}}
        <span class="d-block mb-1">Toplam Dağıtım</span>

        {{-- KPI Değer --}}
<h3 class="card-title text-nowrap mb-2">
    {{ number_format($totalDistributed ?? 0) }}
</h3>

        {{-- KPI Açıklama --}}
        <small class="text-success fw-semibold">
            <i class="bx bx-check-circle"></i> Başarıyla ulaştırıldı
        </small>
    </div>
</div>

                    </div>
                    <div class="col-6 mb-4">
                    <div class="card">
    <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
                <img src="../assets/img/icons/unicons/cc-primary.png" alt="Bekleyen İhtiyaç" class="rounded" />
            </div>

            <div class="dropdown">
                <button
                    class="btn p-0"
                    type="button"
                    id="cardOpt1"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="cardOpt1">
                    <a class="dropdown-item" href="javascript:void(0);">Detayları Gör</a>
                </div>
            </div>
        </div>

        {{-- KPI Başlık --}}
        <span class="fw-semibold d-block mb-1">Bekleyen İhtiyaç</span>

        {{-- KPI Değer --}}
<h3 class="card-title mb-2">
    {{ number_format($pendingNeed ?? 0) }}
</h3>

        {{-- KPI Açıklama --}}
        <small class="text-danger fw-semibold">
            <i class="bx bx-error-circle"></i> Henüz karşılanmadı
        </small>
    </div>
</div>

                    </div>
                    </div>
    <div class="row">
                    <div class="col-12 mb-4">
                      <div class="card">
                        <div class="card-body">
    <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">

        <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
            <div class="card-title">
                <h5 class="text-nowrap mb-2">İhtiyaç Trend Özeti</h5>
                <span class="badge bg-label-warning rounded-pill">Son 7 Gün</span>
            </div>

            <div class="mt-sm-auto">
                <small class="text-{{ $trendPercent >= 0 ? 'danger' : 'success' }} text-nowrap fw-semibold">
    <i class="bx bx-chevron-{{ $trendPercent >= 0 ? 'up' : 'down' }}"></i>
    %{{ abs($trendPercent) }} {{ $trendPercent >= 0 ? 'artış' : 'azalış' }}
</small>

                <h3 class="mb-0">
    {{ number_format($trendTotal ?? 0) }}
</h3>

            </div>
        </div>

        {{-- Mini Line Chart --}}
  <div id="profileReportChart"></div>

    </div>
</div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
             <div class="row">

  <!-- İHTİYAÇ DONUT GRAFİĞİ -->
  <div class="col-lg-4 col-md-12 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="card-title mb-0">İhtiyaç Dağılımı</h5>
      </div>
      <div class="card-body d-flex justify-content-center align-items-center">
        <div id="needTypesChart"></div>
      </div>
    </div>
  </div>
<!-- EKİP DAĞILIMI -->
<div class="col-lg-4 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Ekip Dağılımı</h5>
      <span class="badge bg-label-primary">Aktif</span>
    </div>

   <div class="card-body d-flex justify-content-center align-items-center flex-column">

  <div id="teamDistributionChart"></div>



</div>

  </div>
</div>

  <!-- HARİTA -->
  <div class="col-lg-4 col-md-12 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Kritik Bölgeler Haritası</h5>
        <span class="badge bg-danger">Yüksek İhtiyaç</span>
      </div>
      <div class="card-body p-0">
        <div id="criticalMap"></div>
      </div>
    </div>
  </div>

</div>


              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©️
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , made with ❤️ by
                  <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
                </div>
                <div>
                  <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                  <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                  <a
                    href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                    target="_blank"
                    class="footer-link me-4"
                    >Documentation</a
                  >

                  <a
                    href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                    target="_blank"
                    class="footer-link me-4"
                    >Support</a
                  >
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <div class="buy-now">
      <a
        href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
        >Upgrade to Pro</a
      >
    </div>
<script>
async function assignTeam(regionId, kdsScore) {

  // 🔢 KDS → Öncelik
  let priority = 4;
  if (kdsScore >= 120) priority = 1;
  else if (kdsScore >= 90) priority = 2;
  else if (kdsScore >= 60) priority = 3;

  if (!confirm("Bu bölgeye ekip atamak istiyor musun?")) return;

  try {
    const res = await fetch("http://127.0.0.1:8001/analytics/assign", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        bolge_id: Number(regionId),
        priority: Number(priority)
      })
    });

    const data = await res.json();

    if (!res.ok) {
      alert("❌ " + (data.detail || "Atama başarısız"));
      return;
    }

    // ✅ Kullanıcı bilgilendirme
    alert(
      "🚑 Ekip Yola Çıktı\n\n" +
      "👥 Ekip: " + data.team + "\n" +
      "🏥 Tür: " + data.ekip_turu + "\n" +
      "⏱️ ETA: " + (data.eta_minutes ?? "Bilinmiyor") + " dk\n\n" +
      "🧠 Neden:\n" + data.reason
    );

    // 🔥🔥🔥 HARİTA HAREKETİ (ASİL OLAY)
    if (data.assignment_id && data.team_id) {
      const teamMarker = teamMarkers[data.team_id];

      if (teamMarker) {
        showTeamMovement(data.assignment_id, teamMarker);
      } else {
        console.warn("⚠️ Team marker bulunamadı:", data.team_id);
      }
    }

    // ❌ SAYFA YENİLEME YOK
    // location.reload();

  } catch (e) {
    console.error(e);
    alert("❌ Sunucuya bağlanılamadı");
  }
}
</script>


  </body>
  @push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush

</html>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endpush
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

  const raw = @json($topRegions);

  const categories = raw.map(r => r.region_name);
const seriesData = raw.map(r => Number(r.kds_score));

  const options = {
    series: [{
      name: 'Toplam İhtiyaç',
      data: seriesData
    }],
    chart: {
      type: 'bar',
      height: 350,
      toolbar: { show: false }
    },
    plotOptions: {
      bar: {
        borderRadius: 8,
        columnWidth: '45%',
        distributed: true
      }
    },
    xaxis: {
      categories: categories
    },
    yaxis: {
      title: { text: 'Toplam İhtiyaç' }
    }
  };

  new ApexCharts(
    document.querySelector("#needRegionsChart"),
    options
  ).render();
});
</script>

@endpush
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Laravel'den gelen veri (region bazlı ARRAY)
const raw = @json($needDistribution ?? []);

const totals = {
  water: Number(raw.water || 0),
  food: Number(raw.food || 0),
  tent: Number(raw.tent || 0),
  medicine: Number(raw.medicine || 0)
};


    // Debug (mutlaka console'da kontrol et)
    console.log("İhtiyaç Dağılımı:", totals);

    // ApexCharts Donut
    const options = {
        chart: {
            type: 'donut',
            height: 300
        },
        series: [
            totals.water,
            totals.food,
            totals.tent,
            totals.medicine
        ],
        labels: ['Su', 'Gıda', 'Çadır', 'İlaç'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true
        },
        tooltip: {
            y: {
                formatter: val => val.toLocaleString()
            }
        }
    };

    const chart = new ApexCharts(
        document.querySelector("#needTypesChart"),
        options
    );

    chart.render();
});
</script>

@endpush


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", async function () {

  const map = L.map('criticalMap').setView([37.0662, 37.3833], 6);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

const redIcon = L.divIcon({
    html: `<div style="
        background:red;
        width:16px;
        height:16px;
        border-radius:50%;
        border:3px solid white;
        box-shadow:0 0 8px rgba(255,0,0,0.8);
    "></div>`,
    iconSize: [16,16],
    iconAnchor: [8,8]
});

const greenIcon = L.divIcon({
    html: `<div style="
        background:green;
        width:14px;
        height:14px;
        border-radius:50%;
        border:2px solid white;
    "></div>`,
    iconSize: [14,14],
    iconAnchor: [7,7]
});

const response = await fetch("http://127.0.0.1:8001/analytics/map");
const regions = await response.json();

regions.forEach(region => {

  if (!region.latitude || !region.longitude) return;

  let priorityText = "Düşük";
  let priorityColor = "green";

  if (region.kds_score >= 120) {
    priorityText = "Kritik";
    priorityColor = "red";
  } else if (region.kds_score >= 90) {
    priorityText = "Yüksek";
    priorityColor = "orange";
  }

  const teams = region.suggested_teams?.join(", ") || "Öneri yok";

  L.marker(
    [region.latitude, region.longitude],
    { icon: region.kds_score >= 100 ? redIcon : greenIcon }
  ).addTo(map)
   .bindPopup(`
  <b>${region.ad}</b><br>
  <b>KDS:</b> ${region.kds_score}<br>

  <b>Öncelik:</b>
  <span style="color:${priorityColor}; font-weight:bold;">
    ${priorityText}
  </span>

  <hr>

  <b>Önerilen Ekipler:</b><br>
  ${teams}

  <hr>

  <button
  type="button"
  onclick="assignTeam(${region.id}, ${region.kds_score}); return false;"
  class="btn btn-sm btn-danger mt-2"
>
  🚑 Ekip Ata
</button>

`);

});

  setTimeout(() => map.invalidateSize(), 300);
});
</script>

@endpush
<script>
document.addEventListener("DOMContentLoaded", function () {

  const raw = @json($teamStatus ?? []);

  // 🎯 SABİT DURUM SIRASI
  const STATUS_ORDER = ['Hazır', 'Görevde', 'Dinleniyor'];

  // 🎯 Başlangıç değerleri
  const dataMap = {
    'Hazır': 0,
    'Görevde': 0,
    'Dinleniyor': 0
  };

  // Backend’den gelen veriyi map'e yerleştir
  raw.forEach(item => {
    if (dataMap.hasOwnProperty(item.durum)) {
      dataMap[item.durum] = Number(item.total);
    }
  });

  const series = STATUS_ORDER.map(s => dataMap[s]);
  const labels = STATUS_ORDER;

  const total = series.reduce((a, b) => a + b, 0);

  if (total === 0) {
    document.querySelector("#teamDistributionChart")
      .innerHTML = "<small class='text-muted'>Veri yok</small>";
    return;
  }

  const options = {
    chart: {
      type: 'donut',
      height: 260
    },
    series: series,
    labels: labels,

    // 🎨 RENKLERİ SABİTLE
    colors: ['#0d6efd', '#f59e0b', '#ef4444'],

    dataLabels: {
      formatter: function (val) {
        return val.toFixed(1) + "%";
      }
    },

    legend: {
      position: 'bottom'
    },

    tooltip: {
      y: {
        formatter: val => val + " ekip"
      }
    }
  };

  new ApexCharts(
    document.querySelector("#teamDistributionChart"),
    options
  ).render();
});
</script>
