@extends('layouts.tailwind')

@section('content')
  @include('components.header')

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto mt-3">
    <h1 class="font-bold">10 Paket Strategis</h1>
  </div>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between items-end pb-3">
    <div class="w-full">
      <div class="flex justify-between mb-3">
        <p >Menampilkan 10 Paket Strategis</p>
        <div>
          <a href="#" id="add" class="bg-green-500 p-1 rounded text-sm font-bold text-white">Tambah</a>
          <a href="{{ route('tender.strategic.download') }}" class="bg-blue-500 p-1 rounded text-sm font-bold text-white">Download</a>
        </div>
      </div>
      

      <div class="max-w-100 overflow-auto h-96">
        <table class="min-w-full divide-y divide-black ">
          <thead>
            <tr>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Kode</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Nama Paket</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">OPD</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Pagu Pemenang</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Nama Perusahaan</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">NPWP</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">PAGU</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">HPS</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Alamat</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Implementasi</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($data as $item)
            <tr>
              <td class="px-1 py-2 "><p class="text-sm">{{ $item->code }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm"><a href="{{ route('tender.show', ['code' => $item->tender->kd_tender]) }}" class="text-blue-500">{{ $item->tender->nama_paket }}</a>  </p></td>
              <td class="px-1 py-2 "><p class="text-sm">{{ ($item->tender->satker) ? $item->tender->satker->nama_satker : 'Tidak ditemukan' }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">{{ moneyFormat($item->tender->nilai_kontrak) }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">{{ $item->tender->nama_penyedia }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">{{ $item->tender->npwp_penyedia }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">Rp{{ moneyFormat($item->tender->pagu) }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">Rp{{ moneyFormat($item->tender->hps) }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">{{ $item->address }} </p></td>
              <td class="px-1 py-2 "><p class="text-sm">{{ $item->implementation }} </p></td>
              <td>
                <a href="#" class="bg-green-500 hidden p-1 text-white font-bold text-sm rounded">Edit</a> &nbsp;
                <a href="{{ route('tender.strategic.delete', ['code' => $item->code]) }}" class="bg-red-500 p-1 text-white font-bold text-sm rounded">Hapus</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="pop-up" class="hidden h-screen w-screen fixed z-10 top-0 bottom-0 right-0 left-0">
    <div class="bg-black fixed h-screen w-screen top-0 bottom-0 right-0 left-0 opacity-20"></div>
    <div class="fixed h-screen w-screen top-0 bottom-0 right-0 left-0 z-100 flex justify-center items-start md:items-center pt-10 md:pt-0 ">
        <div class="bg-white w-11/12 md:w-2/3 rounded-md">
          <div class="flex justify-center p-3">
            <div>
              <form action="{{ route('tender.api.list') }}" id="form" method="get"></form>
              <input id="search-input" type="text" name="search" class="md:w-64 rounded border py-2 px-1" placeholder="Cari Nama atau Kode Tender" required form="form">
              <button id="search" class="bg-blue-500 px-3 py-2 font-bold text-white rounded-md ml-1 btn-search" type="submit" form="form">Cari</button>
              <button id="close" class="bg-red-500 px-3 py-2 text-white font-bold rounded-md ml-2 btn-close">Close</button>
            </div>
            
          </div>
          <div class="my-3">
            <p id="loading" class="text-center hidden">Loading...</p>
            <p id="no-data" class="text-center hidden">Data tidak ditemukan</p>
            <div id="data" class="max-w-100 overflow-auto hidden px-3">
              <table class="min-w-full divide-y divide-black ">
                <thead>
                  <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Kode Paket</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Kode Tender</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Nama Paket</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">OPD</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody id="body-data" class="bg-white divide-y divide-gray-200">
                </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
</div>

  @include('components.footer')
@endsection

@push('script')
<script>
const popUp   = document.querySelector('#pop-up')
const btnAdd  = document.querySelector('#add')

const searchInput = document.querySelector('#search-input')
const btnSearch   = document.querySelector('#search')
const btnClose    = document.querySelector('#close')

const textLoading   = document.querySelector('#loading')
const textNoData    = document.querySelector('#no-data')

const divData       = document.querySelector('#data')
const bodyData      = document.querySelector('#body-data')
const form          = document.querySelector('#form')

const addStrategicPackage   = (id) => {
  const body  = JSON.stringify({id})

  fetch('{{ route('tender.strategic.add') }}', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    },
    body,
  })
  .then(response => response.json())
  .then(json => {
    if( json.message ) {
      alert(json.message)
      return
    }

    alert('Berhasil menambah paket')

    location.href = '{{ route('tender.strategic') }}'
  })
  .catch(err => {
    alert(err)
  })
}

if( Boolean(btnAdd) && Boolean(popUp) ) {
  btnAdd.addEventListener('click', ev => {
    ev.preventDefault()
    popUp.classList.remove('hidden')
  })
}

if( Boolean(form) && Boolean(btnClose) ) {
  form.addEventListener('submit', ev => {
    ev.preventDefault()
    const search  = searchInput.value
    const url   = form.getAttribute('action')

    textNoData.classList.add('hidden')
    divData.classList.add('hidden')
    textLoading.classList.remove('hidden')

    fetch(url + '?search=' + search, {
      method: 'GET',
      headers: {
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(json => {
      textLoading.classList.add('hidden')
      if( json.message ) {
        alert(json.message)
        return
      }

      const data = json.data

      if( data.length <= 0 ) {
        textNoData.classList.remove('hidden')
        return
      }

      divData.classList.remove('hidden')

      data.forEach(el => {
        const tr  = document.createElement('tr')

        const tdCode  = document.createElement('td')
        tdCode.setAttribute('class', 'text-center')
        tr.appendChild(tdCode)
        tdCode.textContent = el.kd_paket
        const tdCodeTender  = document.createElement('td')
        tdCodeTender.setAttribute('class', 'text-center')
        tr.appendChild(tdCodeTender)
        tdCodeTender.textContent = el.kd_tender
        const tdName  = document.createElement('td')
        tr.appendChild(tdName)
        tdName.textContent = el.nama_paket
        const tdOPD   = document.createElement('td')
        tr.appendChild(tdOPD)
        if( el.satker ) {
          tdOPD.textContent = el.satker.nama_satker
        }
        
        const tdAction  = document.createElement('td')
        tr.appendChild(tdAction)

        const btnAddPackage   = document.createElement('button')
        btnAddPackage.setAttribute('class', 'bg-green-500 px-2 py-1 rounded-md font-bold text-white text-sm')
        btnAddPackage.textContent = 'Tambah'
        btnAddPackage.addEventListener('click', ev => {
          ev.preventDefault()
          if( confirm('Yakin menambah Paket ' + el.nama_paket + '?') ) {
            addStrategicPackage(el.id)
          }
        })
        tdAction.appendChild(btnAddPackage)

        bodyData.appendChild(tr)
      })

      // <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Kode</th>
      // <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Nama Paket</th>
      // <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">OPD</th>
      // <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Pagu Pemenang</th>
      // <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Nama Perusahaan</th>
      // <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">PAGU</th>
      // <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">HPS</th>
      // <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Aksi</th>
    })
    .catch(err => {
      alert(err)
      textLoading.classList.add('hidden')
    })
  })

  btnClose.addEventListener('click', ev => {
    ev.preventDefault()
    popUp.classList.add('hidden')
  })
}
</script>
@endpush