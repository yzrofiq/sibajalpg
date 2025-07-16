@extends('layouts.adminlte')

@section('content')

<div class="container-fluid px-4 py-4">
  <div class="bg-white p-4 rounded" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h3 class="fw-bold text-primary mb-0">Grafik Pengadaan Barang dan Jasa</h3>
        <p class="text-muted mb-0">Distribusi dan jenis pengadaan berdasarkan tahun {{ $tahun }}</p>
      </div>

      {{-- Filter Tahun --}}
      <form method="GET" id="tahunForm" class="d-flex align-items-center">
        <select id="tahunFilter" name="tahun" class="form-select form-select-sm w-auto ms-2">
          @for ($i = now()->year; $i >= 2020; $i--)
            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
          @endfor
        </select>
      </form>
    </div>

    <div class="row mt-3">
      <!-- Chart 1 -->
      <div class="col-md-6 mb-4">
        <div class="card border">
          <div class="card-header fw-bold">
            Distribusi Pengadaan Tahun {{ $tahun }}
          </div>
          <div class="card-body bg-white">
            <canvas id="chart1" height="227" style="min-height: 227px;"></canvas>
          </div>
        </div>
      </div>

      <!-- Chart 2 -->
      <div class="col-md-6 mb-4">
        <div class="card border">
          <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Pengelompokan Jenis Barang dan Jasa
            <form method="GET" id="kategoriForm">
              <input type="hidden" name="tahun" value="{{ $tahun }}">
              <select id="chart2Filter" name="kategori_chart2" class="form-select form-select-sm w-auto">
                <option value="tender" {{ $kategoriChart2 == 'tender' ? 'selected' : '' }}>Tender</option>
                <option value="non_tender" {{ $kategoriChart2 == 'non_tender' ? 'selected' : '' }}>Non Tender</option>
              </select>
            </form>
          </div>
          <div class="card-body bg-white">
            <canvas id="chart2" height="227" style="min-height: 227px;"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  // ⏰ Update jam real-time
  function updateClock() {
    const now = new Date();
    const jamBox = document.getElementById('jam-box');
    const menitBox = document.getElementById('menit-box');
    const detikBox = document.getElementById('detik-box');
    if (jamBox && menitBox && detikBox) {
      jamBox.textContent = now.getHours().toString().padStart(2, '0');
      menitBox.textContent = now.getMinutes().toString().padStart(2, '0');
      detikBox.textContent = now.getSeconds().toString().padStart(2, '0');
    }
  }
  updateClock();
  setInterval(updateClock, 1000);

  // 👀 Animasi IntersectionObserver
  const box = document.querySelector('.tentang-box.animate-up');
  if (box) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        box.classList.toggle("show", entry.isIntersecting);
      });
    }, { threshold: 0.4 });
    observer.observe(box);
  }

  // 📊 CHART 1 - Distribusi
  const chart1Data = {!! json_encode($chart1Data) !!};
  new Chart(document.getElementById('chart1'), {
    type: 'pie',
    data: {
      labels: Object.keys(chart1Data),
      datasets: [{
        data: Object.values(chart1Data),
        backgroundColor: ['#569FB2', '#FF6A3F']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          align: 'center',
          labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            boxWidth: 10
          }
        }
      }
    }
  });

  // 📊 CHART 2 - Jenis Pengadaan
  const chart2Data = {!! json_encode($chart2Data) !!};
  const chart2Colors = ['#27214D', '#569FB2', '#86D7B7', '#F8E08A', '#FF6A3F', '#5C8DF6'];
  new Chart(document.getElementById('chart2'), {
    type: 'pie',
    data: {
      labels: Object.keys(chart2Data),
      datasets: [{
        data: Object.values(chart2Data),
        backgroundColor: chart2Colors.slice(0, Object.keys(chart2Data).length)
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          align: 'center',
          labels: {
            usePointStyle: true,
            pointStyle: 'circle',
            boxWidth: 10
          }
        }
      }
    }
  });

  // 🔄 Dropdown Filter Auto Submit
  const tahunFilter = document.getElementById('tahunFilter');
  if (tahunFilter) {
    tahunFilter.addEventListener('change', () => {
      document.getElementById('tahunForm').submit();
    });
  }

  const chart2Filter = document.getElementById('chart2Filter');
  if (chart2Filter) {
    chart2Filter.addEventListener('change', () => {
      document.getElementById('kategoriForm').submit();
    });
  }
});
</script>
@endpush
