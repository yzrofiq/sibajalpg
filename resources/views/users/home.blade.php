<!-- File: resources/views/users/home.blade.php -->
@extends('layouts.user')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link href="{{ asset('css/sibaja.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="hero-wrapper">
  <div class="hero-container">
    <div class="hero-box row align-items-center">
      <div class="col-md-6 text-center">
        <img src="{{ asset('images/gubernur-wakil.png') }}" alt="Pimpinan Daerah" class="img-fluid hero-img">
      </div>
      <div class="col-md-6 hero-text">
        <h1>Selamat Datang di Website<br>Sistem Informasi Barang<br>dan Jasa Provinsi Lampung</h1>
        <p class="mt-3">
        Selamat datang di portal resmi Sistem Informasi Barang dan Jasa Provinsi Lampung, sebuah platform digital yang dirancang untuk mendukung transparansi, akuntabilitas, dan kemudahan akses informasi terkait pengadaan barang dan jasa di lingkungan Pemerintah Provinsi Lampung. Melalui website ini, masyarakat, pelaku usaha, serta instansi terkait dapat memantau berbagai proses, data, dan perkembangan pengadaan secara terbuka dan terintegrasi, sehingga diharapkan dapat mewujudkan tata kelola pemerintahan yang lebih baik, bersih, dan berorientasi pada pelayanan publik yang prima.        </p>
        <a href="#tentang" class="hero-btn mt-3">
  Selengkapnya
  <i class="bi bi-arrow-down ms-2"></i>
</a>



      </div>
    </div>
  </div>
</div>

<!-- SECTION: Summary Report -->
<div class="py-4" style="background-color: #F5F8FD;">
  <div class="container-fluid px-4 py-4 d-flex justify-content-center">
    <div class="bg-white p-4 rounded w-100" style="max-width: 1140px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

      <h3 class="fw-bold mb-0 text-primary">Statistik Umum Pengadaan</h3>
      <p class="text-muted">Menampilkan data ringkas mengenai jumlah paket pengadaan dan instansi aktif yang terlibat dalam sistem.</p>

      <div class="row row-cols-1 row-cols-md-2 g-3 mt-4">
        <div class="col">
          <div class="d-flex justify-content-between align-items-center border rounded p-3 bg-white shadow-sm">
            <div class="d-flex align-items-center">
              <div class="p-2 rounded me-3" style="background-color: #DDEEFF;">
                <i class="fas fa-chart-line fs-4" style="color: #3366CC;"></i>
              </div>
              <div>
                <div class="fw-bold fs-4">716</div>
                <div class="text-muted">Non Tender</div>
              </div>
            </div>
            <button class="btn"
  style="background-color: #1d3c77; color: white; transition: background-color 0.2s;"
  onmouseover="this.style.backgroundColor='#2181EF'"
  onmouseout="this.style.backgroundColor='#1d3c77'">
  Lihat
</button>
          </div>
        </div>

        <div class="col">
          <div class="d-flex justify-content-between align-items-center border rounded p-3 bg-white shadow-sm">
            <div class="d-flex align-items-center">
              <div class="p-2 rounded me-3" style="background-color: #DDEEFF;">
                <i class="fas fa-store fs-4" style="color: #3366CC;"></i>
              </div>
              <div>
                <div class="fw-bold fs-4">0</div>
                <div class="text-muted">Total E-Katalog</div>
              </div>
            </div>
            <button class="btn"
  style="background-color: #1d3c77; color: white; transition: background-color 0.2s;"
  onmouseover="this.style.backgroundColor='#2181EF'"
  onmouseout="this.style.backgroundColor='#1d3c77'">
  Lihat
</button>
          </div>
        </div>

        <div class="col">
          <div class="d-flex justify-content-between align-items-center border rounded p-3 bg-white shadow-sm">
            <div class="d-flex align-items-center">
              <div class="p-2 rounded me-3" style="background-color: #DDEEFF;">
                <i class="fas fa-chart-bar fs-4" style="color: #3366CC;"></i>
              </div>
              <div>
                <div class="fw-bold fs-4">0</div>
                <div class="text-muted">Tender</div>
              </div>
            </div>
            <button class="btn"
  style="background-color: #1d3c77; color: white; transition: background-color 0.2s;"
  onmouseover="this.style.backgroundColor='#2181EF'"
  onmouseout="this.style.backgroundColor='#1d3c77'">
  Lihat
</button>
          </div>
        </div>

        <div class="col">
          <div class="d-flex justify-content-between align-items-center border rounded p-3 bg-white shadow-sm">
            <div class="d-flex align-items-center">
              <div class="p-2 rounded me-3" style="background-color: #DDEEFF;">
              <i class="fas fa-shopping-cart fs-4" style="color: #3366CC;"></i>
              </div>
              <div>
                <div class="fw-bold fs-4">48</div>
                <div class="text-muted">Total Toko Daring</div>
              </div>
            </div>
            <button class="btn"
  style="background-color: #1d3c77; color: white; transition: background-color 0.2s;"
  onmouseover="this.style.backgroundColor='#2181EF'"
  onmouseout="this.style.backgroundColor='#1d3c77'">
  Lihat
</button>
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-md-6 mb-4 mb-md-0">
          <div class="card border">
            <div class="card-header fw-bold d-flex justify-content-between">
              Distribusi Pengadaan
              <select class="form-select form-select-sm w-auto">
                <option>2024</option>
                <option>2025</option>

              </select>
            </div>
            <div class="card-body" style="background-color: white;">
              <canvas id="chart1" height="220"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card border">
            <div class="card-header fw-bold">
              Pengelompokan Jenis Barang dan Jasa
            </div>
            <div class="card-body" style="background-color: white;">
              <canvas id="chart2" height="220"></canvas>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>



<!-- SECTION: Transparency Report -->
<section id="reports" style="position: relative; z-index: 1;">

  <!-- Bagian atas: putih -->
  <div class="transparency-section" style="background-color: white; padding: 60px 0 30px 0;">
    <div class="container">
      <h4 class="section-title text-white mb-4">Transparency Reports</h4>

      <div id="transparencyCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

          <!-- SLIDE 1 -->
          <div class="carousel-item active">
            <div class="row g-3">
              <div class="col-md-3">
                <div class="report-card shadow-sm">
                  <h6><span class="text-warning fw-bold">Package</span> Procurement Statistics</h6>
                  <p>Tender: 21</p>
                  <p>Non Tender: 677</p>
                  <p>Total e-Ratalog: 458</p>
                  <a href="#">View Details →</a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="report-card shadow-sm">
                  <h6><span class="text-warning fw-bold">Package</span> Procurement Statistics</h6>
                  <p>Tender: 21</p>
                  <p>Non Tender: 677</p>
                  <p>Total e-Ratalog: 458</p>
                  <a href="#">View Details →</a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="report-card shadow-sm">
                  <h6>Bandar Lampung city<br>Hall Poofing Construction</h6>
                  <p>Code: 1223.50,1635</p>
                  <p>HPS: Rp 2,005,040,000</p>
                  <p>Stage: In Progress</p>
                  <a href="#">View Details →</a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="report-card shadow-sm">
                  <h6>Construction of the RUP<br>New State University<br>Building 2025</h6>
                  <p>Code: 168.903.5525</p>
                  <p>HPS: Rp 18,500,000,000</p>
                  <p>Stage: In Progress</p>
                  <a href="#">View Details →</a>
                </div>
              </div>
            </div>
          </div>

          <!-- SLIDE 2 -->
          <div class="carousel-item">
            <div class="row g-3">
              <div class="col-md-3">
                <div class="report-card shadow-sm">
                  <h6>Example Project 5</h6>
                  <p>Code: 123456</p>
                  <p>HPS: Rp 1,000,000,000</p>
                  <p>Stage: Completed</p>
                  <a href="#">View Details →</a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="report-card shadow-sm">
                  <h6>Example Project 6</h6>
                  <p>Code: 654321</p>
                  <p>HPS: Rp 800,000,000</p>
                  <p>Stage: In Progress</p>
                  <a href="#">View Details →</a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="report-card shadow-sm">
                  <h6>Example Project 7</h6>
                  <p>Code: 222333</p>
                  <p>HPS: Rp 900,000,000</p>
                  <p>Stage: Planning</p>
                  <a href="#">View Details →</a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="report-card shadow-sm">
                  <h6>Example Project 8</h6>
                  <p>Code: 777888</p>
                  <p>HPS: Rp 1,200,000,000</p>
                  <p>Stage: In Progress</p>
                  <a href="#">View Details →</a>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#transparencyCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#transparencyCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>
  </div>

  <!-- Bagian bawah abu muda -->
  <div style="background-color: #F5F8FD; padding: 25px 0;">
    <div class="container">
      <!-- Kosongkan atau isi konten pelengkap jika dibutuhkan -->
    </div>
  </div>
</section>





<!-- Strip Biru Vertikal -->
<div class="strip-wrapper position-relative">
  <div class="vertical-blue-strip"></div>

  <!-- SECTION: Tentang Kami -->
  <section id="tentang" class="tentang-kami-section">
    <div class="background-abu-kanan"></div>

    <div class="container-lg position-relative">
      <div class="kantor-wrapper d-flex justify-content-center">
        <div id="carouselKantor" class="carousel slide kantor-carousel" data-bs-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="{{ asset('images/kantor1.png') }}" class="d-block kantor-carousel-img" alt="Kantor 1">
            </div>
            <!-- Tambahan slide jika perlu -->
          </div>
        </div>
      </div>

      <div class="tentang-box animate-up shadow">
        <h6 class="text-primary fw-semibold mb-1">Tentang Kami</h6>
        <h4 class="fw-bold mb-5">LPSE - Biro Pengadaan Barang dan Jasa Provinsi Lampung</h4>
        <div class="mb-0">
  <h6 class="fw-bold mb-4">VISI DAN MISI</h6>
  <div class="mb-3">
    <strong>VISI</strong><br>
    <em>"Terwujudnya Layanan Pengadaan Barang / Jasa Kualitas Prima Dan Berkesinambungan"</em>
  </div>

  <div>
    <strong>MISI</strong>
    <ol class="mt-2 mb-0 ps-3">
      <li>
        Memberikan Layanan kepada Pengguna Sistem Pengadaan Secara Elektronik (SPSE)
        yang sederhana, Cepat, Tepat, Berkesinambungan dan Akuntabel Tanpa Dipungut Biaya.
      </li>
      <li>
        Mengedepankan Pelayanan Yang Tertib Administrasi, Tertib Hukum dan Tertib Pelaksanaan.
      </li>
    </ol>
  </div>
</div>

        <!-- Tambahkan spacer DIV setelah teks -->
<div style="height: 3rem;"></div>
      </div>
    </div>
  </section>
</div>


<!-- SECTION: Transparansi Keuangan Daerah -->
<section class="transparansi-wrapper position-relative py-5">
<div class="background-abu-kanan transparansi-abu"></div>

  <div class="container position-relative">
    <div class="transparansi-card shadow-lg p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <div>
          <h4 class="fw-bold text-dark">Transparansi Keuangan Daerah</h4>
          <p class="text-muted">Menyajikan Publikasi Kinerja Keuangan Daerah Provinsi Jawa Barat</p>
        </div>
        <a href="#" class="hero-btn fw-semibold mt-2 mt-md-0">
          Lihat Semua Laporan <i class="bi bi-box-arrow-up-right ms-1"></i>
        </a>
      </div>

      <div id="carouselPDF" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
          <!-- Slide 1 -->
          <div class="carousel-item active">
            <div class="d-flex gap-3 flex-wrap justify-content-center">
              <div class="pdf-card">
                <div class="text-center mb-2">
                  <img src="{{ asset('images/pdf-icon.png') }}" width="150" alt="PDF">
                </div>
                <small class="text-muted">Transparansi Pengelolaan Keuangan Daerah</small>
                <h6 class="fw-bold mt-1">Neraca Tahun 2024</h6>
                <p class="desc-text">Informasi Neraca Tahun 2024 per 31 Desember 2024 dan 2023</p>
                <div class="d-flex gap-2 mt-2">
                <a href="#" class="btn btn-sm btn-pdf-outline w-100"><i class="bi bi-eye"></i> Lihat</a>
                <a href="#" class="btn btn-sm hero-btn w-100 d-flex justify-content-center align-items-center">
  <i class="bi bi-download me-1"></i> Unduh
</a>

                </div>
              </div>
              <div class="pdf-card">
                <div class="text-center mb-2">
                  <img src="{{ asset('images/pdf-icon.png') }}" width="150" alt="PDF">
                </div>
                <small class="text-muted">Transparansi Pengelolaan Keuangan Daerah</small>
                <h6 class="fw-bold mt-1">Laporan Arus Kas Tahun 2024</h6>
                <p class="desc-text">Laporan Arus Kas Tahun 2024 dan perbandingan 2023</p>
                <div class="d-flex gap-2 mt-2">
                <a href="#" class="btn btn-sm btn-pdf-outline w-100"><i class="bi bi-eye"></i> Lihat</a>
                <a href="#" class="btn btn-sm hero-btn w-100 d-flex justify-content-center align-items-center">
  <i class="bi bi-download me-1"></i> Unduh
</a>

                </div>
              </div>
            </div>
          </div>

          <!-- Slide 2 -->
          <div class="carousel-item">
            <div class="d-flex gap-3 flex-wrap justify-content-center">
              <div class="pdf-card">
                <div class="text-center mb-2">
                  <img src="{{ asset('images/pdf-icon.png') }}" width="150" alt="PDF">
                </div>
                <small class="text-muted">Transparansi Pengelolaan Keuangan Daerah</small>
                <h6 class="fw-bold mt-1">Peraturan Kepala Daerah</h6>
                <p class="desc-text">Peraturan Gubernur Tahun 2024 Tentang Kebijakan Akuntansi</p>
                <div class="d-flex gap-2 mt-2">
                <a href="#" class="btn btn-sm btn-pdf-outline w-100"><i class="bi bi-eye"></i> Lihat</a>
                <a href="#" class="btn btn-sm hero-btn w-100 d-flex justify-content-center align-items-center">
  <i class="bi bi-download me-1"></i> Unduh
</a>
                </div>
              </div>
              <div class="pdf-card">
                <div class="text-center mb-2">
                  <img src="{{ asset('images/pdf-icon.png') }}" width="150" alt="PDF">
                </div>
                <small class="text-muted">Transparansi Pengelolaan Keuangan Daerah</small>
                <h6 class="fw-bold mt-1">Anggaran Pendapatan</h6>
                <p class="desc-text">Perubahan Anggaran Pendapatan Daerah 2024</p>
                <div class="d-flex gap-2 mt-2">
                <a href="#" class="btn btn-sm btn-pdf-outline w-100"><i class="bi bi-eye"></i> Lihat</a>
                <a href="#" class="btn btn-sm hero-btn w-100 d-flex justify-content-center align-items-center">
  <i class="bi bi-download me-1"></i> Unduh
</a>

                </div>
              </div>
            </div>
          </div>
        </div>

         <!-- Navigasi PANAH (tetap di dalam kotak putih) -->
         <button class="carousel-control-prev inside-arrow" type="button" data-bs-target="#carouselPDF" data-bs-slide="prev">
  <span class="carousel-control-prev-icon"></span>
</button>
<button class="carousel-control-next inside-arrow" type="button" data-bs-target="#carouselPDF" data-bs-slide="next">
  <span class="carousel-control-next-icon"></span>
</button>


<!-- Bullets -->
<div class="carousel-indicators mt-4">
  <button type="button" data-bs-target="#carouselPDF" data-bs-slide-to="0" 
    class="active indicator-dot"></button>
  <button type="button" data-bs-target="#carouselPDF" data-bs-slide-to="1" 
    class="indicator-dot"></button>
</div>


</section>



@endsection


@push('styles')
<style>
  .btn-primary-custom {
    background-color: #3366CC;
    color: white;
    border: none;
    transition: background-color 0.3s ease;
  }

  .btn-primary-custom:hover {
    background-color: #254a99;
    color: white;
  }

  /* Optional: fokus/active */
  .btn-primary-custom:focus, .btn-primary-custom:active {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(51, 102, 204, 0.25);
  }
</style>
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  new Chart(document.getElementById('chart1'), {
    type: 'pie',
    data: {
      labels: ['Tender', 'Non Tender'],
      datasets: [{
        data: [30, 70],
        backgroundColor: ['#3366CC', '#66B2FF']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle' } }
      }
    }
  });

  new Chart(document.getElementById('chart2'), {
    type: 'pie',
    data: {
      labels: ['Barang', 'Jasa', 'Lainnya', 'Konstruksi'],
      datasets: [{
        data: [35, 25, 15, 25],
        backgroundColor: ['#66B2FF', '#3366CC', '#A7C5EB', '#4A90E2']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle' } }
      }
    }
  });
</script>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    const box = document.querySelector('.tentang-box.animate-up');

    if (!box) return;

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          box.classList.add("show");
        } else {
          box.classList.remove("show");
        }
      });
    }, { threshold: 0.4 });

    observer.observe(box);
  });
</script>
<script>
  function updateClock() {
    const now = new Date();

    const jam = now.getHours().toString().padStart(2, '0');
    const menit = now.getMinutes().toString().padStart(2, '0');
    const detik = now.getSeconds().toString().padStart(2, '0');

    document.getElementById('jam-box').textContent = jam;
    document.getElementById('menit-box').textContent = menit;
    document.getElementById('detik-box').textContent = detik;
  }

  document.addEventListener("DOMContentLoaded", function () {
    updateClock();
    setInterval(updateClock, 1000);
  });
</script>



@endpush







