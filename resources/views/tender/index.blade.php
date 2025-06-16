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
      <input type="text" name="code" class="w-full rounded border py-2 px-1" placeholder="Kode" form="form" value="{{ $code }}">
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-2/5 pr-0 md:pr-3">
      <p>Nama Paket</p>
      <input type="text" name="name" class="w-full rounded border py-2 px-1" placeholder="Nama Paket" form="form" value="{{ $name }}">
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-2/5 pr-0 md:pr-3">
      <p>Satuan Kerja</p>

      <div class="w-full relative">
        <input type="hidden" name="kd_satker" id="kd_satker" form="form">
        <input id="kd_satker_placeholder" type="text" placeholder="Nama Satker" class="w-full rounded border py-2 px-1">
        <ul id="satker-options" class="bg-white max-h-32 absolute float-left w-full  mt-2 px-3 overflow-auto hidden options">          
        </ul>
      </div>
    </div>

    <div class="w-full mb-1 md:mb-0 md:w-1/12 pr-0 md:pr-3">
      <p>Tahun</p>
      <select name="year" id="year" class="w-full rounded border py-2 px-1" form="form">
        <option value="">--Pilih--</option>
        @foreach ($years as $item)
        <option value="{{ $item }}" @if ( $year AND $year == $item ) selected @endif>{{ $item }}</option>    
        @endforeach
        
      </select>
    </div>

    <div class="w-full md:w-1/12 pr-0 md:pr-3">
      <button type="submit" form="form" class="bg-blue-600 text-white rounded py-2 px-3 w-full">Cari</button>
    </div>
  </div>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between mt-2 items-end pt-3 pb-1">
    <div class="w-full">
      <a href="{{ route('non-tender.list') }}" class="text-sm @if( !$categoryParam ) bg-gray-300 @else bg-gray-200 @endif py-1 px-2 rounded-lg inline-block mb-1">Semua ({{ $totalFull }})</a>
      @foreach ($categories as $key => $value)
      <a href="{{ $url . '&category=' . $value }}" class="text-sm @if( $categoryParam == $value ) bg-gray-300 @else bg-gray-200 @endif py-1 px-2 rounded-lg inline-block mb-1">{{ $value . ' (' . $categoriesCount[$key] . ')' }}</a>
      @endforeach
    </div>
  </div>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between items-end pb-3">
    <div class="w-full">
      <p class="mb-3">Menampilkan {{ count($data) }} dari total {{ $total }} data</p>

      <div class="max-w-100 overflow-auto h-96">
        <table class="min-w-full divide-y divide-black ">
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
            @foreach ($data as $item)
            <tr>
              <td class="px-1 py-2 "><p class="text-sm">{{ $item->kd_tender }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm"><a href="{{ route('tender.show', ['code' => $item->kd_tender]) }}" class="text-blue-500">{{ $item->nama_paket }}</a>  </p></td>
              <td class="px-1 py-2 "><p class="text-sm">{{ $item->nama_klpd }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">{{ $item->current_schedule }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">Rp{{ $item->hps }} </p></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('components.footer')
@endsection

@push('script')
<script>
  const satkerInputPlaceholder  = document.querySelector('#kd_satker_placeholder')
  const satkerInput   = document.querySelector('#kd_satker')
  const satkerOptions = document.querySelector('#satker-options')
  const satkers = @json($satkers)
  
  const selectedSatker  = "{{ $satkerCode }}"
  
  
  const showSatkerOptions = () => {
    satkerOptions.classList.remove('hidden')
  }
  
  const hideSatkerOptions = () => {
    satkerOptions.classList.add('hidden')
  }
  
  const selectRawSatker  = (code, value) => {
    satkerInput.value   = code
    satkerInputPlaceholder.value = value
    hideSatkerOptions()
  }
  
  const selectSatker  = (ev) => {
    const el  = ev.target
    const code  = el.getAttribute('data-code')
    satkerInput.value   = code
    satkerInputPlaceholder.value = el.innerHTML
    hideSatkerOptions()
  }
  
  const buildSatker   = (value) => {
  
    satkerOptions.innerHTML = ''
    const firstLi = document.createElement('li')
    firstLi.textContent = 'SEMUA'
    firstLi.addEventListener('click', ev => {
      selectRawSatker('', '')
      hideSatkerOptions()
    })
    satkerOptions.appendChild(firstLi)
  
    if( !value ) {
      satkers.forEach(el => {
        const li = document.createElement('li')
        li.setAttribute('data-code', el.kd_satker_str)
        li.textContent = el.nama_satker  
        if( el.kd_satker_str == value ) {
          selectRawSatker(el.kd_satker_str, el.nama_satker)
        }
        li.addEventListener('click', selectSatker)
        satkerOptions.appendChild(li)
      })
      
    } else {
      
      const returns = []
  
      satkers.forEach(el => {
        const lowerValue  = value.toLowerCase()
        const lowerData   = el.nama_satker.toLowerCase()
        
        const resultName  = lowerData.includes(lowerValue)
        const resultCode  = el.kd_satker_str.includes(lowerValue)
  
        if( resultName || resultCode ) {
          returns.push(el)
        }
      })
  
      if( returns.length > 0 ) {
        satkerOptions.innerHTML = ''
      }
      
      returns.forEach(el => {
        const li = document.createElement('li')
        li.setAttribute('data-code', el.kd_satker_str)
        li.textContent = el.nama_satker  
        if( el.kd_satker_str == value ) {
          selectRawSatker(el.kd_satker_str, el.nama_satker)
        }
        li.addEventListener('click', selectSatker)
        satkerOptions.appendChild(li)
      })
    }
  }
  
  satkerInputPlaceholder.addEventListener('focus', ev => {
    buildSatker(selectedSatker)
    showSatkerOptions()
  })
  
  satkerInputPlaceholder.addEventListener('keyup', ev => {
    buildSatker(ev.target.value)
  })
  
  buildSatker(selectedSatker)
  
  </script>  
@endpush