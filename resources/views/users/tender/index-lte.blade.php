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
  .small-box .inner h3,
  .small-box .inner p { color: inherit !important; }
</style>
@endpush

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
        <input id="codeInput" type="text" placeholder="Kode" class="form-control" value="{{ $code }}">
      </div>
      <div class="col-md-3">
        <label for="name">Nama Paket</label>
        <input id="nameInput" type="text" placeholder="Nama Paket" class="form-control" value="{{ $name }}">
      </div>
      <div class="col-md-5">
      <label for="satkerSelect">Satuan Kerja</label>
<select id="satkerSelect" class="form-select" onchange="handleSatkerChange()">
  <option value="">-- Semua Satker --</option>
  @foreach ($satkers as $satker)
    <option value="{{ $satker->nama_satker }}" {{ $satkerCode == $satker->nama_satker ? 'selected' : '' }}>
      {{ $satker->nama_satker }}
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
      <a href="{{ route('tender.list') }}" class="text-sm @if(!$categoryParam) bg-success @else bg-secondary @endif py-1 px-2 d-inline-block mb-1 rounded">
        Semua ({{ $totalFull }})
      </a>

    
      @foreach ($categoriesCount as $category => $count)
  @if ($count > 0)
    <a href="{{ request()->fullUrlWithQuery(['category' => $category]) }}" 
       class="text-sm {{ $categoryParam == $category ? 'bg-success' : 'bg-secondary' }} py-1 px-2 d-inline-block rounded mb-1">
      {{ $category }} ({{ $count }})
    </a>
  @endif
@endforeach


    </div>
  </div>
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
  function submitForm() {
    document.getElementById('form').submit();
  }

  function handleEnterSubmit(inputId, paramName) {
  const input = document.getElementById(inputId);
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      const form = document.getElementById('form');

      // Hapus input lama
      const existing = form.querySelector(`input[name="${paramName}"]`);
      if (existing) existing.remove();

      // Tambahkan input baru
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = paramName;
      hiddenInput.value = input.value;
      form.appendChild(hiddenInput);

      // Tambahkan input Satker (jaga-jaga agar tidak hilang)
      const satkerSelect = document.getElementById('satkerSelect');
      if (satkerSelect) {
        const satkerInput = document.createElement('input');
        satkerInput.type = 'hidden';
        satkerInput.name = 'satker';
        satkerInput.value = satkerSelect.value;
        form.appendChild(satkerInput);
      }

      form.submit();
    }
  });
}
function handleSatkerChange() {
  const select = document.getElementById('satkerSelect');
  const selectedValue = select.value;
  const form = document.getElementById('form');

  form.innerHTML = ''; // Bersihkan isian form lama

  const satkerInput = document.createElement('input');
  satkerInput.type = 'hidden';
  satkerInput.name = 'satker'; // Tetap kirim sebagai 'satker'
  satkerInput.value = selectedValue;
  form.appendChild(satkerInput);

  const params = ['code', 'name', 'year', 'category']; // Tambahkan category!
  params.forEach(param => {
    const value = new URLSearchParams(window.location.search).get(param);
    if (value) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = param;
      input.value = value;
      form.appendChild(input);
    }
  });

  form.submit();
}

  handleEnterSubmit('codeInput', 'code');
  handleEnterSubmit('nameInput', 'name');
</script>
@endpush
