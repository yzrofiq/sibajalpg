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

<form action="" method="get" id="form"></form>

<section class="content">
  <div class="container-fluid">
    @include('components.summary')

    <div class="row">
      <div class="col-md-2">
        <label class="m-0" for="code">Kode</label>
        <input form="form" id="code" type="text" name="code" placeholder="Kode" class="form-control" value="{{ $code }}">
      </div>
      <div class="col-md-3">
        <label class="m-0" for="name">Nama Paket</label>
        <input form="form" id="name" type="text" name="name" placeholder="Nama Paket" class="form-control" value="{{ $name }}">
      </div>
      <div class="col-md-5">
        <label class="m-0" for="kd_satker">Satuan Kerja</label>
        
        <select form="form" name="kd_satker" id="kd_satker" class="form-control select2">
          <option value="">--Pilih---</option>
          @foreach ($satkers as $item)
            <option value="{{ $item->kd_satker_str }}" @if ( $satkerCode == $item->kd_satker_str ) selected @endif >{{ $item->nama_satker }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-1">
        <label class="m-0" for="code">Tahun</label>
        <select form="form" name="year" id="year" class="form-control" form="form" >
          <option value="">--Pilih---</option>
          @foreach ($years as $item)
            <option value="{{ $item }}" @if ( $year AND $year == $item ) selected @endif>{{ $item }}</option>    
          @endforeach
        </select>
      </div>
      <div class="col-md-1">
        <label for="search" class="m-0">Cari</label>
        <button type="submit" form="form" class="btn bg-primary">Cari</button>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-12">

        <div class="mb-3">
          <a href="{{ route('non-tender.list') }}" class="text-sm @if( !$categoryParam ) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block mb-1 rounded ">Semua ({{ $totalFull }})</a>
          @foreach ($categories as $key => $value)
          <a href="{{ $url . '&category=' . $value }}" class="text-sm @if( $categoryParam == $value ) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block  rounded mb-1">{{ $value . ' (' . $categoriesCount[$key] . ')' }}</a>
          @endforeach
        </div>

        <div class="card">
          <div class="card-body table-responsive p-0">
            <table class="table table-head-fixed table-hover">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Nama Paket</th>
                  <th>K/L/PD</th>
                  <th>Tahapan</th>
                  <th>HPS</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $key => $item)
                <tr>
                  <td><p class="text-sm">{{ $item->kd_nontender }} </p></td>
                  <td><p class="text-sm"><a href="{{ route('non-tender.show', ['code' => $item->kd_nontender]) }}" target="_blank" class="text-blue-500">{{ $item->nama_paket }}</a>  </p></td>
                  <td><p class="text-sm">{{ $item->nama_klpd }} </p></td>
                  <td><p class="text-sm">{{ $item->current_schedule }} </p></td>
                  <td><p class="text-sm">Rp{{ $item->hps }} </p></td>
                </tr>
                @endforeach
              </tbody>
            </table>
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
   $('.select2').select2()
 })
</script>
@endpush