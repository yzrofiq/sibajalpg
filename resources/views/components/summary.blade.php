<div class="row">
    <div class="col-lg-3 col-md-6">
      <div class="small-box bg-info">
        <div class="inner">
        <h3>{{ moneyFormat($tenderCount ?? 0) }}</h3>
          <p>Tender</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="{{ route('tender.list') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ moneyFormat($nonTenderCount ?? 0) }}</h3>
        <p>Non Tender</p>
      </div>
      <div class="icon">
        <i class="ion ion-pie-graph"></i>
      </div>
      <a href="{{ route('non-tender.list') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  {{-- e-Katalog --}}
  <div class="col-lg-3 col-md-6">
    <div class="small-box bg-success">
      <div class="inner">
      <h3>{{ number_format(getEkatalogCount(), 0, ',', '.') }}</h3>
        <p>Total e-Katalog</p>
      </div>
      <div class="icon">
        <i class="ion ion-pie-graph"></i>
      </div>
      <a href="{{ route('report.ekatalog') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>

  {{-- Toko Daring (Bela Pengadaan) --}}
<div class="col-lg-3 col-md-6">
  <div class="small-box bg-danger">
    <div class="inner">
    <h3>{{ number_format(getBelaCount(), 0, ',', '.') }}</h3>
      <p>Toko Daring</p>
    </div>
    <div class="icon">
      <i class="ion ion-pie-graph"></i>
    </div>
    <a href="{{ route('report.tokodaring') }}" class="small-box-footer">
      Lihat <i class="fas fa-arrow-circle-right"></i>
    </a>
    @if (Auth::user()->role_id == 1)
      <a href="{{ route('bela.update') }}" class="small-box-footer text-warning">
        Update Data
      </a>
    @endif
  </div>
</div>


</div>
