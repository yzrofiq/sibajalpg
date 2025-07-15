@extends('layouts.adminlte')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Tender</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">Tender</li>
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
        <label for="satker">Satuan Kerja</label>
        <select form="form" name="satker" id="satker" class="form-control select2" onchange="submitForm()">
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
          <a href="{{ route('tender.list') }}" class="text-sm @if(!$categoryParam) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block mb-1 rounded">Semua ({{ $totalFull }})</a>
          @foreach ($categories as $key => $value)
            <a href="{{ $url . '&category=' . urlencode($value) }}" class="text-sm @if($categoryParam == $value) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block rounded mb-1">{{ $value . ' (' . ($categoriesCount[$key] ?? 0) . ')' }}</a>
          @endforeach
        </div>

        <div class="card">
          <div class="card-body table-responsive p-0">
            <table class="table table-head-fixed table-hover">
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
                @forelse ($data as $item)
                  <tr>
                    <td><p class="text-sm">{{ $item->kd_tender }}</p></td>
                    <td><p class="text-sm">
                      <a href="{{ route('tender.show', ['code' => $item->kd_tender]) }}" class="text-blue-500" target="_blank">
                        {{ $item->nama_paket }}
                      </a></p>
                    </td>
                    <td><p class="text-sm">{{ $item->status_tender ?? '-' }}</p></td>
                    <td><p class="text-sm">{{ \App\Services\HelperService::moneyFormat($item->hps) }}</p></td>
                    <td><p class="text-sm">{{ isset($item->nilai_pdn_kontrak) ? \App\Services\HelperService::moneyFormat($item->nilai_pdn_kontrak) : '-' }}</p></td>
                    <td><p class="text-sm">{{ isset($item->nilai_umk_kontrak) ? \App\Services\HelperService::moneyFormat($item->nilai_umk_kontrak) : '-' }}</p></td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
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
