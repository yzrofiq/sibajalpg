@extends('layouts.adminlte')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Non Tender</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">Non Tender</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    @include('components.summary')

    {{-- Filter utama --}}
    <form id="mainFilterForm" method="GET" action="">
      <div class="row">
        <div class="col-md-2">
          <label for="code">Kode</label>
          <input id="code" name="code" type="text" placeholder="Kode" class="form-control" value="{{ $code }}">
        </div>
        <div class="col-md-3">
          <label for="name">Nama Paket</label>
          <input id="name" name="name" type="text" placeholder="Nama Paket" class="form-control" value="{{ $name }}">
        </div>
        <div class="col-md-5">
          <label for="kd_satker">Satuan Kerja</label>
          <select id="kd_satker" name="kd_satker" class="form-control select2">
            <option value="">--Pilih---</option>
            @foreach ($satkers as $item)
              <option value="{{ $item->kd_satker }}" {{ $satkerCode == $item->kd_satker ? 'selected' : '' }}>
                {{ $item->nama_satker }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label for="year">Tahun</label>
          <select id="year" name="year" class="form-control">
            <option value="">--Pilih---</option>
            @foreach ($years as $item)
              <option value="{{ $item }}" {{ $year == $item ? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>

    @php
      // urlBase selalu membawa filter (tanpa &category)
      $urlBase = url()->current()
        . '?year=' . urlencode($year)
        . '&kd_satker=' . urlencode($satkerCode)
        . '&code=' . urlencode($code)
        . '&name=' . urlencode($name);
    @endphp

    <div class="row mt-3">
      <div class="col-12">
        {{-- Bar kategori --}}
        <div class="mb-3" id="kategori-bar">
          <a href="{{ $urlBase }}" class="text-sm @if(!$categoryParam) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block mb-1 rounded">
            Semua ({{ $totalFull }})
          </a>
          @foreach ($categories as $key => $value)
            <a href="{{ $urlBase . '&category=' . urlencode($value) }}"
              class="text-sm @if($categoryParam == $value) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block rounded mb-1">
              {{ $value . ' (' . ($categoriesCount[$key] ?? 0) . ')' }}
            </a>
          @endforeach
        </div>

        <div class="card">
          <div class="card-body table-responsive p-0">
            <table class="table table-head-fixed table-hover" id="nontenderTable">
              <thead>
                <tr>
                  <th>Kode Non Tender</th>
                  <th>Nama Paket</th>
                  <th>Status Non Tender</th>
                  <th>HPS</th>
                  <th>Nilai PDN Kontrak</th>
                  <th>Nilai UMK Kontrak</th>
                </tr>
              </thead>
              <tbody>
                @include('components.tables.nontender-rows', ['data' => $data])
              </tbody>
            </table>
          </div>
          <div class="card-footer clearfix">
            <div class="pagination-container">
              {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('script')
<script>
$(function () {
  $('.select2').select2();

  // --- FORM FILTER ---
  // Tahun & Satker: Reload (submit form)
  $('#year, #kd_satker').on('change', function () {
    $('#mainFilterForm').submit();
  });

  // --- AJAX Search: Kode & Nama Paket ---
  let debounceTimer;
  $('#code, #name').on('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function() {
      fetchFilteredData(1);
    }, 300);
  });

  function fetchFilteredData(page = 1) {
    const code = $('#code').val();
    const name = $('#name').val();
    const kd_satker = $('#kd_satker').val();
    const year = $('#year').val();
    const category = getKategoriFromUrl();

    $.ajax({
      url: "{{ route('non-tender.search') }}?page=" + page,
      data: { code, name, kd_satker, year, category },
      success: function (response) {
        $('#nontenderTable tbody').html(response.html);
        $('.pagination-container').html(response.pagination);
        handlePagination();
      },
      error: function () {
        $('#nontenderTable tbody').html('<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>');
        $('.pagination-container').html('');
      }
    });
  }

  // Dapatkan category dari URL agar konsisten saat search/paginate
  function getKategoriFromUrl() {
    const url = new URL(window.location.href);
    return url.searchParams.get('category') || '';
  }

  // --- AJAX PAGINATION (hanya berlaku untuk search AJAX) ---
  function handlePagination() {
    $('.pagination a').off('click').on('click', function (e) {
      e.preventDefault();
      const url = new URL($(this).attr('href'), window.location.origin);
      const page = url.searchParams.get('page') || 1;
      fetchFilteredData(page);
    });
  }

  handlePagination();

  // --- Optional: biar search kode/nama bisa langsung submit form (Enter), disable default submit (hanya AJAX) ---
  $('#code, #name').on('keydown', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
    }
  });
});
</script>
@endpush



