@extends('layouts.tailwind')

@section('content')
  @include('components.header')
  @extends('layouts.tailwind')

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto mt-3">
    <h1 class="font-bold">Tender</h1>
  </div>

  <form action="" method="get" id="form"></form>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between mt-2 items-end py-3 pl-3 rounded border border-blue-400">
    <div class="w-full mb-1 md:mb-0 md:w-1/5 pr-0 md:pr-3">
      <p>Kode</p>
      <input type="text" name="code" class="w-full rounded border py-2 px-1" placeholder="Kode" form="form" value="{{ $code }}">
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-2/5 pr-0 md:pr-3">
      <p>Nama Paket</p>
      <input type="text" name="name" class="w-full rounded border py-2 px-1" placeholder="Nama Paket" form="form" value="{{ $name }}">
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-2/5 pr-0 md:pr-3">
      <p>Satuan Kerja</p>
      <div class="w-full relative">
        <input type="hidden" name="satker" id="kd_satker" form="form">
        <input id="kd_satker_placeholder" type="text" placeholder="Nama Satker" class="w-full rounded border py-2 px-1">
        <ul id="satker-options" class="bg-white max-h-32 absolute float-left w-full mt-2 px-3 overflow-auto hidden options z-10 border border-gray-300 rounded shadow-md"></ul>
      </div>
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-1/12 pr-0 md:pr-3">
      <p>Tahun</p>
      <select name="year" id="year" class="w-full rounded border py-2 px-1" form="form">
        <option value="">--Pilih--</option>
        @foreach ($years as $item)
        <option value="{{ $item }}" @if ($year && $year == $item) selected @endif>{{ $item }}</option>
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
  const satkerInput = document.getElementById('kd_satker');
  const satkerInputPlaceholder = document.getElementById('kd_satker_placeholder');
  const satkerOptions = document.getElementById('satker-options');

  const satkers = @json($satkers); // Harus berisi kd_satker & nama_satker
  const selectedSatker = "{{ $satkerCode }}";

  const showSatkerOptions = () => satkerOptions.classList.remove('hidden');
  const hideSatkerOptions = () => satkerOptions.classList.add('hidden');

  const selectSatker = (ev) => {
    const el = ev.target;
    const code = el.getAttribute('data-code');
    const name = el.textContent;

    satkerInput.value = code;
    satkerInputPlaceholder.value = name;
    hideSatkerOptions();
  };

  const buildSatker = (filter = '') => {
    satkerOptions.innerHTML = '';
    const filtered = filter
      ? satkers.filter(el => el.nama_satker.toLowerCase().includes(filter.toLowerCase()) || el.kd_satker.includes(filter))
      : satkers;

    const semuaOption = document.createElement('li');
    semuaOption.textContent = 'SEMUA';
    semuaOption.className = 'cursor-pointer py-1 hover:bg-gray-100';
    semuaOption.addEventListener('click', () => {
      satkerInput.value = '';
      satkerInputPlaceholder.value = '';
      hideSatkerOptions();
    });
    satkerOptions.appendChild(semuaOption);

    filtered.forEach(el => {
      const li = document.createElement('li');
      li.textContent = el.nama_satker;
      li.setAttribute('data-code', el.kd_satker);
      li.className = 'cursor-pointer py-1 hover:bg-gray-100';
      li.addEventListener('click', selectSatker);
      satkerOptions.appendChild(li);
    });
  };

  satkerInputPlaceholder.addEventListener('focus', () => {
    buildSatker();
    showSatkerOptions();
  });

  satkerInputPlaceholder.addEventListener('keyup', (ev) => {
    buildSatker(ev.target.value);
    showSatkerOptions();
  });

  document.addEventListener('click', (e) => {
    if (!satkerOptions.contains(e.target) && e.target !== satkerInputPlaceholder) {
      hideSatkerOptions();
    }
  });

  // Set default jika ada
  if (selectedSatker) {
    const found = satkers.find(s => s.kd_satker === selectedSatker);
    if (found) {
      satkerInput.value = found.kd_satker;
      satkerInputPlaceholder.value = found.nama_satker;
    }
  }
</script>
@endpush
