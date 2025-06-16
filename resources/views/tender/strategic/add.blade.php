@extends('layouts.tailwind')

@section('content')
  @include('components.header')

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto mt-3">
    <h1 class="font-bold">Tambah Paket Strategis</h1>
  </div>

  <div class="w-full md:w-4/5 px-3 md:px-0 mx-auto block md:flex justify-between items-end pb-3">
    <div class="w-full">
      <div class="flex justify-between mb-3">
        <p >Menampilkan 10 Paket Strategis</p>
        <div>
          <a href="{{ route('tender.strategic.download') }}" class="bg-green-500 p-1 rounded text-sm font-bold text-white">Tambah</a>
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
                <a href="#" class="bg-green-500 p-1 text-white font-bold text-sm rounded">Edit</a> &nbsp;
                <a href="{{ route('tender.strategic.delete', ['code' => $item->code]) }}" class="bg-red-500 p-1 text-white font-bold text-sm rounded">Hapus</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @include('components.footer')
@endsection
