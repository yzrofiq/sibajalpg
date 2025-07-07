@extends('layouts.user-adminlte')

@push('style')
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/sibaja.css') }}">

<style>
  /* Prioritaskan warna asli AdminLTE untuk small-box */
  .small-box.bg-warning    { background-color: #ffc107 !important; color: #000 !important; }
  .small-box.bg-info       { background-color: #17a2b8 !important; color: #fff !important; }
  .small-box.bg-success    { background-color: #28a745 !important; color: #fff !important; }
  .small-box.bg-danger     { background-color: #dc3545 !important; color: #fff !important; }
  .small-box .inner h3,
  .small-box .inner p {
    color: inherit !important;
  }
</style>


@endpush


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

<form action="" method="get" id="form"></form>

<section class="content">
  <div class="container-fluid">
    @include('components.summary')

    <div class="row">
      <div class="col-md-2">
        <label for="code">Kode</label>
        <input form="form" id="code" type="text" name="code" placeholder="Kode" class="form-control" value="{{ $code }}" oninput="submitForm()">
      </div>
      <div class="col-md-3">
        <label for="name">Nama Paket</label>
        <input form="form" id="name" type="text" name="name" placeholder="Nama Paket" class="form-control" value="{{ $name }}" oninput="submitForm()">
      </div>
      <div class="col-md-5">
        <label for="kd_satker">Satuan Kerja</label>
        <select form="form" name="kd_satker" id="kd_satker" class="form-control select2" onchange="submitForm()">
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
        <select form="form" name="year" id="year" class="form-control" onchange="submitForm()">
          <option value="">--Pilih---</option>
          @foreach ($years as $item)
            <option value="{{ $item }}" {{ $year == $item ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-12">

        <div class="mb-3">
          <a href="{{ route('non-tender.list') }}" class="text-sm @if(!$categoryParam) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block mb-1 rounded">Semua ({{ $totalFull }})</a>
          @foreach ($categories as $key => $value)
            <a href="{{ $url . '&category=' . $value }}" class="text-sm @if($categoryParam == $value) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block rounded mb-1">{{ $value . ' (' . $categoriesCount[$key] . ')' }}</a>
          @endforeach
        </div>

        <div class="card">
          <div class="card-body table-responsive p-0">
            <table class="table table-head-fixed table-hover">
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
                @forelse ($data as $item)
                  <tr>
                    <td><p class="text-sm">{{ $item->kd_nontender }}</p></td>
                    <td><p class="text-sm"><a href="#" class="text-blue-500">{{ $item->nama_paket }}</a></p></td>
                    <td><p class="text-sm">{{ $item->status_nontender ?? '-' }}</p></td>
                    <td><p class="text-sm">{{ \App\Services\HelperService::moneyFormat($item->hps) }}</p></td>
                    <td><p class="text-sm">{{ isset($item->nilai_pdn_kontrak) ? \App\Services\HelperService::moneyFormat($item->nilai_pdn_kontrak) : '-' }}</p></td>
                    <td><p class="text-sm">{{ isset($item->nilai_umk_kontrak) ? \App\Services\HelperService::moneyFormat($item->nilai_umk_kontrak) : '-' }}</p></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center">Tidak ada data ditemukan</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="card-footer clearfix">
            {{ $data->links('pagination::bootstrap-4') }}
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
  });

  function submitForm() {
    document.getElementById('form').submit();
  }
</script>
@endpush
