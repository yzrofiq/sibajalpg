@extends('layouts.adminlte')

@push('style')

<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    @include('components.summary')

    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">10 Paket Strategis</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('tender.list') }}">Tender</a></li>
          <li class="breadcrumb-item"><a>10 Paket Strategis</a></li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
              <h3 class="card-title">10 Paket Strategis</h3>
              <div>
              @if ( Auth::user()->role_id == 1 AND count($data) < 10 )
              <button type="button" class="btn btn-sm btn-success font-weight-bold" data-toggle="modal" data-target="#tender-modal">
                Tambah
              </button>    
              @endif
              <a target="_blank" href="{{ route('tender.strategic.download') }}" class="btn btn-sm btn-primary font-weight-bold">Download</a>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
            <table id="strategic" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode</th>
                  <th>Nama Paket</th>
                  <th>OPD</th>
                  <th>Pagu Pemenang</th>
                  <th>Nama Perusahaan</th>
                  <th>NPWP</th>
                  <th>Pagu</th>
                  <th>HPS</th>
                  <th>Alamat</th>
                  <th>Implementasi</th>
                  @if ( Auth::user()->role_id == 1 )
                  <th>Aksi</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @if ( count($data) )
                @foreach ($data as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->code }}</td>
                  <td><a href="{{ route('tender.show', ['code' => $item->tender->kd_tender]) }}" class="text-blue-500">{{ $item->tender->nama_paket }}</a> </td>
                  <td>{{ ($item->tender->satker) ? $item->tender->satker->nama_satker : 'Tidak ditemukan' }}</td>
                  <td>{{ moneyFormat($item->tender->nilai_kontrak) }}</td>
                  <td>{{ $item->tender->nama_penyedia }}</td>
                  <td>{{ $item->tender->npwp_penyedia }}</td>
                  <td>Rp{{ moneyFormat($item->tender->pagu) }}</td>
                  <td>Rp{{ moneyFormat($item->tender->hps) }}</td>
                  <td>{{ $item->address }}</td>
                  <td>{{ $item->implementation }}</td>
                  @if ( Auth::user()->role_id == 1 )
                  <td>
                    <button data-id="{{ $item->id }}" data-name="{{ $item->tender->nama_paket }}" class="ml-1 btn btn-sm btn-danger delete-confirm">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>    
                  @endif
                </tr>
                @endforeach
                @else
                <tr>
                  <td @if( Auth::user()->role_id == 1 ) colspan="12" @else colspan="11" @endif class="text-center">Belum Ada Data Paket Strategis</td>
                </tr>
                @endif
              </tbody>
              <tfoot>
                <tr>
                  <th>No</th>
                  <th>Kode</th>
                  <th>Nama Paket</th>
                  <th>OPD</th>
                  <th>Pagu Pemenang</th>
                  <th>Nama Perusahaan</th>
                  <th>NPWP</th>
                  <th>Pagu</th>
                  <th>HPS</th>
                  <th>Alamat</th>
                  <th>Implementasi</th>
                  @if ( Auth::user()->role_id == 1 )
                  <th>Aksi</th>
                  @endif
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>

    </div>

  </div>
</section>

@if ( Auth::user()->role_id == 1 AND count($data) < 10)
<!-- Modal -->
<div class="modal fade" id="tender-modal" tabindex="-1" role="dialog" aria-labelledby="tenderTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tenderLongTitle">Tender</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body table-responsive">
        <table id="tender" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Paket</th>
              <th>OPD</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tenders as $item)
            @php
            $insert = true;
            @endphp
            @foreach ($data as $value)
            @if ( $value->code == $item->kd_tender )
            @php
            $insert = false;
            @endphp
            @endif
            @endforeach
            @if ( !$insert )
            @continue
            @endif
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $item->kd_tender }}</td>
              <td><a href="{{ route('tender.show', ['code' => $item->kd_tender]) }}" target="_blank">{{ $item->nama_paket }}</a></td>
              <td>{{ ($item->satker) ? $item->satker->nama_satker : 'Tidak ditemukan' }}</td>
              <td>
              <button data-id="{{ $item->id }}" data-name="{{ $item->nama_paket }}" class="ml-1 btn btn-sm btn-success add-package">
                <i class="fas fa-plus"></i>
              </button>
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Nama Paket</th>
              <th>OPD</th>
              <th>Aksi</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@push('script')

<script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script>
  $(function () {
    $('#data').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#tender').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  $(document).ready(function () {
    $('.delete-confirm').click(function(event) {
      var id = $(this).data('id');
      var name = $(this).data('name');
      event.preventDefault();
      Swal.fire({
        title: `Hapus "${name}" dari daftar paket strategis?`,
        text: 'Anda tidak akan dapat mengembalikan ini!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
      }).then(result => {
        if (result.isConfirmed) {
          $.ajax({
            url: `/tender/strategic-package/delete/${id}`,
            type: 'POST',
            data: {
              id: id,
              '_token': '{{ csrf_token() }}',
            },
            success: function () {
              Swal.fire({
                title: 'Success',
                text: 'Hapus Paket dari daftar Paket Strategis!',
                icon: 'success',
                dangerMode: true,
              }).then(function() {
                location.reload();
              });
            },
            error: function (xhr, status, error) {
              var err = JSON.parse(xhr.responseText);
              Swal.fire({
                title: 'Error',
                text: err?.Message,
                icon: 'error',
              });
            }
          })
        }
      });
    })

    $('.add-package').click(function(event) {
      var id = $(this).data('id');
      var name = $(this).data('name');
      event.preventDefault();
      Swal.fire({
        title: `Tambah "${name}" ke daftar paket strategis?`,
        text: '',
        icon: 'success',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tambah!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
      }).then(result => {
        if (result.isConfirmed) {
          $.ajax({
            url: `/tender/strategic-package/add`,
            type: 'POST',
            data: {
              id: id,
              '_token': '{{ csrf_token() }}',
            },
            success: function () {
              Swal.fire({
                title: 'Success',
                text: 'Tambah Paket ke Paket Strategis!',
                icon: 'success',
                dangerMode: true,
              }).then(function() {
                location.reload();
              });
            },
            error: function (xhr, status, error) {
              var err = JSON.parse(xhr.responseText);
              Swal.fire({
                title: 'Error',
                text: err?.Message,
                icon: 'error',
              });
            }
          })
        }
      });
    })
  });
</script>
@endpush
