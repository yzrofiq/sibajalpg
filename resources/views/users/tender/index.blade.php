@extends('layouts.tailwind')

@section('content')
  @include('components.header')

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto mt-3">
    <h1 class="font-bold">Tender</h1>
  </div>

  <form action="" method="get" id="form"></form>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between mt-2 items-end py-3 pl-3 rounded border border-blue-400">
    <div class="w-full mb-1 md:mb-0 md:w-1/5 pr-0 md:pr-3">
      <p>Kode</p>
      <input type="text" class="w-full rounded border py-2 px-1" placeholder="Kode" value="{{ $code }}" id="codeInput">
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-2/5 pr-0 md:pr-3">
      <p>Nama Paket</p>
      <input type="text" class="w-full rounded border py-2 px-1" placeholder="Nama Paket" value="{{ $name }}" id="nameInput">
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-2/5 pr-0 md:pr-3">
      <p>Satuan Kerja</p>
      <div class="w-full relative">
      <input type="hidden" name="satker" id="satker" form="form" value="{{ $satkerCode }}">
      <input id="satker_placeholder" type="text" placeholder="Nama Satker" class="w-full rounded border py-2 px-1"
  value="{{ $satkers->firstWhere('nama_satker', $satkerCode)->nama_satker ?? '' }}">

        <ul id="satker-options" class="bg-white max-h-32 absolute float-left w-full mt-2 px-3 overflow-auto hidden options z-50 border border-gray-300 rounded shadow"></ul>
      </div>
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-1/12 pr-0 md:pr-3">
      <p>Tahun</p>
      <select form="form" name="year" id="year" class="form-control" onchange="submitForm()">
        <option value="">--Pilih---</option>
        @foreach ($years as $item)
          <option value="{{ $item }}" {{ $year == $item ? 'selected' : '' }}>{{ $item }}</option>
        @endforeach
      </select>
    </div>

    <div class="w-full md:w-1/12 pr-0 md:pr-3">
      <button type="submit" form="form" class="bg-blue-600 text-white rounded py-2 px-3 w-full">Cari</button>
    </div>
  </div>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between mt-2 items-end pt-3 pb-1">
    <div class="w-full">
      <a href="{{ route('tender.list') }}" class="text-sm @if(!$categoryParam) bg-gray-300 @else bg-gray-200 @endif py-1 px-2 rounded-lg inline-block mb-1">Semua ({{ $totalFull }})</a>
      @foreach ($categories as $key => $value)
        <a href="{{ $url . '&category=' . $value }}" class="text-sm @if($categoryParam == $value) bg-gray-300 @else bg-gray-200 @endif py-1 px-2 rounded-lg inline-block mb-1">{{ $value . ' (' . ($categoriesCount[$key] ?? 0) . ')' }}</a>
      @endforeach
    </div>
  </div>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between items-end pb-3">
    <div class="w-full">
      <p class="mb-3">Menampilkan {{ count($data) }} dari total {{ $total }} data</p>

      <div class="max-w-100 overflow-auto h-96">
        <table class="min-w-full divide-y divide-black">
          <thead>
            <tr>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Kode</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Nama Paket</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">K/L/PD</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Tahapan</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">HPS</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Nilai PDN</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Nilai UMK</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($data as $item)
              <tr>
                <td class="px-1 py-2 text-sm text-center">{{ $item->kd_tender ?? '-' }}</td>
                <td class="px-1 py-2 text-sm">
                  <a href="{{ route('tender.show', ['code' => $item->kd_tender]) }}" class="text-blue-500">
                    {{ $item->nama_paket ?? '-' }}
                  </a>
                </td>
                <td class="px-1 py-2 text-sm text-center">{{ $item->nama_klpd ?? '-' }}</td>
                <td class="px-1 py-2 text-sm text-center">{{ $item->current_schedule ?? '-' }}</td>
                <td class="px-1 py-2 text-sm text-right">Rp{{ number_format($item->hps ?? 0, 0, ',', '.') }}</td>
                <td class="px-1 py-2 text-sm text-right">Rp{{ number_format($item->nilai_pdn_kontrak ?? 0, 0, ',', '.') }}</td>
                <td class="px-1 py-2 text-sm text-right">Rp{{ number_format($item->nilai_umk_kontrak ?? 0, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data yang ditemukan</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('components.footer')
@endsection

@push('script')
<script>
  const satkerInputPlaceholder = document.querySelector('#satker_placeholder');
const satkerInput = document.querySelector('#satker');
const satkerOptions = document.querySelector('#satker-options');
const satkers = @json($satkers);
const selectedSatker = "{{ $satkerCode }}";

const showSatkerOptions = () => satkerOptions.classList.remove('hidden');
const hideSatkerOptions = () => satkerOptions.classList.add('hidden');

const selectRawSatker = (value) => {
  satkerInput.value = value;
  satkerInputPlaceholder.value = value;
  hideSatkerOptions();
  document.getElementById('form').submit();
};

const selectSatker = (ev) => {
  const el = ev.target;
  const value = el.getAttribute('data-nama');
  satkerInput.value = value;
  satkerInputPlaceholder.value = el.innerHTML;
  hideSatkerOptions();
  document.getElementById('form').submit();
};

const buildSatker = (value) => {
  satkerOptions.innerHTML = '';
  const firstLi = document.createElement('li');
  firstLi.textContent = 'SEMUA';
  firstLi.classList.add('cursor-pointer', 'hover:bg-blue-100', 'py-1');
  firstLi.addEventListener('click', () => selectRawSatker(''));
  satkerOptions.appendChild(firstLi);

  const filtered = value
    ? satkers.filter(el => el.nama_satker.toLowerCase().includes(value.toLowerCase()) || el.kd_satker_str.includes(value))
    : satkers;

  filtered.forEach(el => {
    const li = document.createElement('li');
    li.setAttribute('data-nama', el.nama_satker); // gunakan nama_satker sebagai value
    li.textContent = el.nama_satker;
    li.classList.add('cursor-pointer', 'hover:bg-blue-100', 'py-1');
    li.addEventListener('click', selectSatker);
    satkerOptions.appendChild(li);
  });
};

satkerInputPlaceholder.addEventListener('focus', () => {
  buildSatker(selectedSatker);
  showSatkerOptions();
});

satkerInputPlaceholder.addEventListener('keyup', (ev) => {
  buildSatker(ev.target.value);
});

document.addEventListener('click', (e) => {
  if (!satkerInputPlaceholder.contains(e.target) && !satkerOptions.contains(e.target)) {
    hideSatkerOptions();
  }
});

</script>
@endpush
