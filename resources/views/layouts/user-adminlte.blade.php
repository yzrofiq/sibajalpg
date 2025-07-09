@extends('layouts.adminlte')

@section('hideSidebar', 'true')

@section('navbar')
  @include('layouts.partials.user-navbar')
  @yield('navbar-extra')
@endsection

@section('footer')
  @include('layouts.partials.user-footer')
@endsection

@section('content')
  <div class="container">
    <!-- Konten utama halaman user di sini -->

    <!-- Tombol untuk membuka modal -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">Buka Filter</button>
  </div>
@endsection

@section('modals')
{{-- Modal Filter --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Download Realisasi Non Tender</h5>
                <!-- Tombol Close (Bootstrap 5) -->
<!-- Bootstrap 4 Modal Close Button -->
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
            </div>
            <div class="modal-body">
                <label for="year">Tahun:</label>
                <select id="year" class="form-control">
                    @isset($years)
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    @else
                        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                    @endisset
                </select>

                <label for="month">Bulan:</label>
                <select id="month" class="form-control">
                    <option value="ALL" selected>KESELURUHAN</option> <!-- Default memilih Keseluruhan -->
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}">{{ strtoupper(getMonthName($month)) }}</option>
                    @endforeach
                </select>

                <label for="day" id="dayLabel" style="display: none;">Tanggal:</label>
                <select id="day" class="form-control" style="display: none;">
                    <option value="ALL">KESELURUHAN</option>
                    <!-- Tanggal akan ditampilkan setelah memilih bulan -->
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="viewButton">Lihat</button>
                <button type="button" class="btn btn-success" id="downloadButton">Download</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Set default value for month dropdown to "Keseluruhan"
    document.getElementById('month').value = 'ALL';

    // Fungsi untuk mengisi dropdown tanggal berdasarkan bulan
    document.getElementById('month').addEventListener('change', function() {
        let month = this.value;
        let daysInMonth = new Date(2024, month, 0).getDate(); // Jumlah hari dalam bulan
        let daySelect = document.getElementById('day');
        let dayLabel = document.getElementById('dayLabel');

        daySelect.innerHTML = '<option value="ALL">KESSELURUHAN</option>'; // Default

        // Isi dropdown tanggal berdasarkan bulan
        if (month != 'ALL') {
            for (let i = 1; i <= daysInMonth; i++) {
                let option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                daySelect.appendChild(option);
            }

            // Menampilkan label dan dropdown tanggal setelah bulan dipilih
            dayLabel.style.display = 'block';
            daySelect.style.display = 'block';
        } else {
            // Jika bulan "Keseluruhan", sembunyikan dropdown tanggal
            dayLabel.style.display = 'none';
            daySelect.style.display = 'none';
        }
    });

    // Fungsi untuk tombol "Lihat"
    document.getElementById('viewButton').addEventListener('click', function() {
        let year = document.getElementById('year').value;
        let month = document.getElementById('month').value;
        let day = document.getElementById('day').value;

        // Arahkan ke tampilan PDF sesuai dengan filter
        window.location.href = `/non-tender/view-pdf?year=${year}&month=${month}&day=${day}`;
    });

    // Fungsi untuk tombol "Download"
    document.getElementById('downloadButton').addEventListener('click', function() {
        let year = document.getElementById('year').value;
        let month = document.getElementById('month').value;
        let day = document.getElementById('day').value;

        // Unduh PDF sesuai dengan filter
        window.location.href = `/non-tender/download-pdf?year=${year}&month=${month}&day=${day}`;
    });
  });
</script>
@endsection
