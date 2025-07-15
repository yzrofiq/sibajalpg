@extends('layouts.tailwind')

@section('content')
  @include('components.header')

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto mt-3">
    <h1 class="font-bold">Non Tender</h1>
  </div>

  <form action="" method="get" id="form">
    <input type="hidden" name="kd_satker" id="kd_satker" value="{{ $satkerCode }}">
  </form>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between mt-2 items-end py-3 pl-3 rounded border border-blue-400">
    <div class="w-full mb-1 md:mb-0 md:w-1/5 pr-0 md:pr-3">
      <p>Kode</p>
      <input type="text" name="code" id="codeInput" class="w-full rounded border py-2 px-1" placeholder="Kode" form="form" value="{{ $code }}">
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-2/5 pr-0 md:pr-3">
      <p>Nama Paket</p>
      <input type="text" name="name" id="nameInput" class="w-full rounded border py-2 px-1" placeholder="Nama Paket" form="form" value="{{ $name }}">
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-2/5 pr-0 md:pr-3">
      <p>Satuan Kerja</p>
      <div class="w-full relative">
        <input id="kd_satker_placeholder" type="text" placeholder="Nama Satker" class="w-full rounded border py-2 px-1" value="{{ $satkers->firstWhere('kd_satker_str', $satkerCode)->nama_satker ?? '' }}">
        <ul id="satker-options" class="bg-white max-h-32 absolute float-left w-full mt-2 px-3 overflow-auto hidden options z-10 border border-gray-300 rounded shadow"></ul>
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
      <a href="{{ route('non-tender.list') }}" class="text-sm @if(!$categoryParam) bg-gray-300 @else bg-gray-200 @endif py-1 px-2 rounded-lg inline-block mb-1">Semua ({{ $totalFull }})</a>
      @foreach ($categories as $key => $value)
        @if(($categoriesCount[$key] ?? 0) > 0)
          <a href="{{ $url . '&category=' . $value }}" class="text-sm @if($categoryParam == $value) bg-gray-300 @else bg-gray-200 @endif py-1 px-2 rounded-lg inline-block mb-1">{{ $value . ' (' . $categoriesCount[$key] . ')' }}</a>
        @endif
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
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($data as $key => $item)
              <tr @if($key % 2 == 0) class="bg-gray-200" @endif>
                <td class="px-1 py-2 text-sm text-center">{{ $item->kd_nontender }}</td>
                <td class="px-1 py-2 text-sm">
                  <a href="#" class="text-blue-500">{{ $item->nama_paket }}</a>
                </td>
                <td class="px-1 py-2 text-sm text-center">{{ $item->nama_klpd }}</td>
                <td class="px-1 py-2 text-sm text-center">{{ $item->status_nontender ?? '-' }}</td>
                <td class="px-1 py-2 text-sm text-right">Rp{{ number_format($item->hps ?? 0, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-sm py-3">Tidak ada data ditemukan</td>
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
  const satkerInput = document.getElementById('kd_satker');
  const satkerInputPlaceholder = document.getElementById('kd_satker_placeholder');
  const satkerOptions = document.getElementById('satker-options');
  const satkers = @json($satkers);
  const selectedSatker = "{{ $satkerCode }}";

  const hideSatkerOptions = () => satkerOptions.classList.add('hidden');
  const showSatkerOptions = () => satkerOptions.classList.remove('hidden');

  const selectSatker = (code, name) => {
    satkerInput.value = code;
    satkerInputPlaceholder.value = name;
    hideSatkerOptions();
    document.getElementById('form').submit();
  };

  const buildSatkerList = (query = '') => {
    satkerOptions.innerHTML = '';
    const allLi = document.createElement('li');
    allLi.textContent = 'SEMUA';
    allLi.classList.add('cursor-pointer', 'hover:bg-gray-100', 'py-1');
    allLi.onclick = () => selectSatker('', '');
    satkerOptions.appendChild(allLi);

    const filtered = query
      ? satkers.filter(el =>
          el.nama_satker.toLowerCase().includes(query.toLowerCase()) ||
          el.kd_satker_str.includes(query)
        )
      : satkers;

    filtered.forEach(el => {
      const li = document.createElement('li');
      li.textContent = el.nama_satker;
      li.dataset.code = el.kd_satker_str;
      li.classList.add('cursor-pointer', 'hover:bg-gray-100', 'py-1');
      li.onclick = () => selectSatker(el.kd_satker_str, el.nama_satker);
      satkerOptions.appendChild(li);
    });
  };

  satkerInputPlaceholder.addEventListener('focus', () => {
    buildSatkerList();
    showSatkerOptions();
  });

  satkerInputPlaceholder.addEventListener('input', e => {
    buildSatkerList(e.target.value);
    showSatkerOptions();
  });

  document.addEventListener('click', function (e) {
    if (!satkerInputPlaceholder.contains(e.target) && !satkerOptions.contains(e.target)) {
      hideSatkerOptions();
    }
  });

  // Enter untuk input kode & nama
  ['codeInput', 'nameInput'].forEach(id => {
    const input = document.getElementById(id);
    if (input) {
      input.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
          e.preventDefault();
          document.getElementById('form').submit();
        }
      });
    }
  });
</script>
@endpush
