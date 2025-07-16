@extends('layouts.user-adminlte')

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/sibaja.css') }}">
<style>
  .small-box.bg-warning { background-color: #ffc107 !important; color: #000 !important; }
  .small-box.bg-info { background-color: #17a2b8 !important; color: #fff !important; }
  .small-box.bg-success { background-color: #28a745 !important; color: #fff !important; }
  .small-box.bg-danger { background-color: #dc3545 !important; color: #fff !important; }
  .small-box .inner h3, .small-box .inner p { color: inherit !important; }
</style>
@endpush

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0">Tender</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right"><li class="breadcrumb-item active">Tender</li></ol>
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
            <table id="tenderTable" class="table table-head-fixed table-hover">
              <thead>
                <tr>
                  <th>Kode Tender</th>
                  <th>Nama Paket</th>
                  <th>Status Tender</th>
                  <th>HPS</th>
                  <th>Nilai PDN Kontrak</th>
                  <th>Nilai UMK Kontrak</th>
                </tr>
              </thead>
              <tbody>
                @include('components.tables.tender-rows', ['data' => $data])
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
  initialData = $('#tenderTable tbody').html();

  $('.select2').select2();

  $('#filter-kode, #filter-nama').on('keyup', function () {
    searchTender();
  });

  function searchTender(page = 1) {
    const code = $('#filter-kode').val().trim();
    const name = $('#filter-nama').val().trim();
    const kd_satker = $('#kd_satker').val();
    const year = $('#year').val();
    const category = '{{ $categoryParam }}';

    $.ajax({
      url: "{{ route('tender.search') }}",
      data: { code, name, kd_satker, year, category, page },
      success: function (response) {
        $('#tenderTable tbody').html(response.html);
        
        if(response.lastPage > 1){
          $('.pagination-container').html(response.pagination).show();
        } else {
          $('.pagination-container').hide();
        }
      },
      error: function () {
        $('#tenderTable tbody').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>');
        $('.pagination-container').hide();
      }
    });
  }

  $(document).on('click', '.pagination-container a', function(e) {
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    searchTender(page);
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
