<div class="row">
    <div class="col-lg-3 col-md-6">
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ moneyFormat(getTenderCount()) }}</h3>
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
          <h3>{{ moneyFormat(getNonTenderCount()) }}</h3>
          <p>Non Tender</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="{{ route('non-tender.list') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ moneyFormat(getBela()) }}</h3>
          <p>Total BeLa</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        @if ( Auth::user()->role_id == 1 )
        <a href="{{ route('bela.update') }}" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        @endif
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>48</h3>
          <p>SatKer</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="https://sirup.lkpp.go.id/sirup/ro/rekap/klpd/D264" class="small-box-footer" target="_blank">Lihat <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>