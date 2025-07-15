@extends('layouts.adminlte')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Grafik Summary Report</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">Grafik Summary Report</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tender dan Non Tender</h3>
          </div>
          <div class="card-body">
            <canvas id="tender-non-tender" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">
            </canvas>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Pengelompokkan Jenis Pengadaan Barang dan Jasa</h3>
          </div>
          <div class="card-body">
            <canvas id="categorize" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">
            </canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('script')
<script>
$(function() {
    // tender non tender
    var pieChartCanvas = $('#tender-non-tender').get(0).getContext('2d')
    var pieData = {
            labels: ['Non Tender','Tender',],
            datasets: [
            {
                data: [{{ getNonTenderCount() }}, {{ getTenderCount() }}],
                backgroundColor: ['#f56954', '#00a65a'],
            }
            ]
        }
    var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
    }
    
    new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    })

    // fund source
    var pieChartCanvas = $('#categorize').get(0).getContext('2d')
    var pieData = {
            labels: ['Pekerjaan Konstruksi','Jasa Konsultansi','Pengadaan Barang', 'Jasa Lainnya'],
            datasets: [
            {
                data: [117, 165, 55, 41],
                backgroundColor: ['#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
            ]
        }
    var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
    }
    
    new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    })
})
</script>
@endpush