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
        <input id="filter-kode" type="text" placeholder="Kode" class="form-control">
      </div>
      <div class="col-md-3">
        <label for="filter-nama">Nama Paket</label>
        <input id="filter-nama" type="text" placeholder="Nama Paket" class="form-control">
      </div>
      <div class="col-md-5">
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
    </div>

    @php
      // URL filter base tanpa kategori
      $urlBase = url()->current()
        . '?year=' . urlencode($year)
        . '&kd_satker=' . urlencode($satkerCode)
        . '&code=' . urlencode($code)
        . '&name=' . urlencode($name);
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
            @if ($data->count() > 10)
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
let initialData = null;

$(function () {
  initialData = $('#nontenderTable tbody').html();

  $('.select2').select2();

  $('#filter-kode, #filter-nama').on('keyup', function () {
    searchNonTender();
  });

  function searchNonTender(page = 1) {
  const code = $('#filter-kode').val().trim();
  const name = $('#filter-nama').val().trim();
  const kd_satker = $('#kd_satker').val();
  const year = $('#year').val();
  const category = '{{ $categoryParam }}';

  $.ajax({
    url: "{{ route('non-tender.search') }}",
    data: { code, name, kd_satker, year, category, page },
    success: function (response) {
      $('#nontenderTable tbody').html(response.html);

      if(response.lastPage > 1){
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

// Trigger ketika klik pagination yang muncul dari AJAX
$(document).on('click', '.pagination-container a', function(e) {
  e.preventDefault();
  let page = $(this).attr('href').split('page=')[1];
  searchNonTender(page);
});


  window.filterBySatker = function() {
    const satker = document.getElementById('kd_satker').value;
    const year = document.getElementById('year').value;
    const url = new URL(window.location.href.split('?')[0]);
    if (satker) url.searchParams.set('kd_satker', satker);
    if (year) url.searchParams.set('year', year);
    window.location.href = url.toString();
  }
});
</script>
@endpush