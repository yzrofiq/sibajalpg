@extends('layouts.adminlte')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0">Non Tender</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right"><li class="breadcrumb-item active">Non Tender</li></ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    @include('components.summary')

    <div class="row">
      <div class="col-md-2">
        <label for="filter-kode">Kode</label>
        <input id="filter-kode" type="text" placeholder="Kode" class="form-control" value="{{ request('code') }}">
      </div>
      <div class="col-md-3">
        <label for="filter-nama">Nama Paket</label>
        <input id="filter-nama" type="text" placeholder="Nama Paket" class="form-control" value="{{ request('name') }}">
      </div>
      <div class="col-md-3">
        <label for="kd_satker">Satuan Kerja</label>
        <select name="kd_satker" id="kd_satker" class="form-control select2" onchange="filterBySatker()">
          <option value="">--Semua Satuan Kerja---</option>
          @foreach ($satkers as $item)
            <option value="{{ $item->kd_satker_str }}" {{ $satkerCode == $item->kd_satker_str ? 'selected' : '' }}>
              {{ $item->nama_satker }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label for="year">Tahun</label>
        <select name="year" id="year" class="form-control" onchange="filterBySatker()">
          <option value="">--Pilih---</option>
          @foreach ($years as $item)
            <option value="{{ $item }}" {{ $year == $item ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label for="status_nontender">Status</label>
        <select name="status_nontender" id="status_nontender" class="form-control" onchange="filterBySatker()">
          @foreach ($statusList as $key => $val)
            <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>{{ $val }}</option>
          @endforeach
        </select>
      </div>
    </div>

    @php
      $urlBase = url()->current()
        . '?year=' . urlencode($year)
        . '&kd_satker=' . urlencode($satkerCode)
        . '&code=' . urlencode($code)
        . '&name=' . urlencode($name)
        . '&status_nontender=' . urlencode($status);
    @endphp

    <div class="row mt-3">
      <div class="col-12">

        <div class="mb-3">
          <a href="{{ $urlBase }}" class="text-sm @if(!$categoryParam) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block mb-1 rounded">
            Semua ({{ $totalFull }})
          </a>
          @foreach ($categories as $key => $value)
            <a href="{{ $urlBase . '&category=' . urlencode($value) }}" class="text-sm @if($categoryParam == $value) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block rounded mb-1">
              {{ $value . ' (' . $categoriesCount[$key] . ')' }}
            </a>
          @endforeach
        </div>

        <div class="card">
          <div class="card-body table-responsive p-0">
            <table id="nontenderTable" class="table table-head-fixed table-hover">
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
            @if ($data->lastPage() > 1)
              <div class="pagination-container">
                {{ $data->links('pagination::bootstrap-4') }}
              </div>
            @endif
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('script')
<script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(function () {
  $('.select2').select2();

  // Keep the category param up-to-date in JS
  let categoryParam = @json($categoryParam);

  // Always reset to page 1 when search box changed
  $('#filter-kode, #filter-nama').on('keyup', function () {
    searchNonTender(1);
  });

  // Pagination click AJAX
  $(document).on('click', '.pagination-container a', function(e) {
    e.preventDefault();
    let url = new URL($(this).attr('href'), window.location.origin);
    let page = url.searchParams.get('page') || 1;
    searchNonTender(page);
  });

  function searchNonTender(page = 1) {
    const code = $('#filter-kode').val().trim();
    const name = $('#filter-nama').val().trim();
    const kd_satker = $('#kd_satker').val();
    const year = $('#year').val();
    const status_nontender = $('#status_nontender').val();

    $.ajax({
      url: "{{ route('non-tender.search') }}",
      data: {
        code, name, kd_satker, year,
        category: categoryParam,
        status_nontender,
        page
      },
      success: function (response) {
        $('#nontenderTable tbody').html(response.html);
        if (response.lastPage > 1) {
          $('.pagination-container').html(response.pagination).show();
        } else {
          $('.pagination-container').hide();
        }
      },
      error: function () {
        $('#nontenderTable tbody').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>');
        $('.pagination-container').hide();
      }
    });
  }

  // Dropdowns reload page (biar SEO friendly)
  window.filterBySatker = function() {
    const satker = $('#kd_satker').val();
    const year = $('#year').val();
    const status = $('#status_nontender').val();
    const url = new URL(window.location.href.split('?')[0]);
    if (satker) url.searchParams.set('kd_satker', satker);
    if (year) url.searchParams.set('year', year);
    if (status) url.searchParams.set('status_nontender', status);
    else url.searchParams.delete('status_nontender');
    window.location.href = url.toString();
  }
});
</script>
@endpush
