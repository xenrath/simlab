@extends('layouts.app')

@section('title', 'Surat Bebas')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Surat Bebas</h1>
  </div>
  <div class="section-body">
    @if (count($pinjams) || count($detailpinjams))
    <div class="card">
      <div class="card-header">
        <h4>Surat Bebas</h4>
      </div>
      <div class="card-body">
        <div class="empty-state" data-height="400">
          <div class="empty-state-icon bg-danger">
            <i class="fas fa-times"></i>
          </div>
          <h2>Tidak dapat mengunduh Surat Bebas Lab</h2>
          <p class="lead">
            Anda masih memiliki peminjaman dan tagihan yang belum diselesaikan.
            <br>
            Silahkan hubungi Laboran yang terkait.
          </p>
          <a href="{{ url('peminjam/suratbebas') }}" class="btn btn-warning my-4">Refresh</a>
        </div>
      </div>
    </div>
    @else
    <div class="card">
      <div class="card-header">
        <h4>Surat Bebas</h4>
      </div>
      <div class="card-body">
        <div class="empty-state" data-height="400">
          <div class="empty-state-icon bg-primary">
            <i class="fas fa-check"></i>
          </div>
          <h2>Silahkan mengunduh Surat Bebas Lab</h2>
          <p class="lead">
            Terimakasih telah menggunakan <b>SIMLAB</b>.
          </p>
          <a href="{{ url('peminjam/suratbebas/cetak') }}" class="btn btn-primary my-4">Unduh</a>
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
<script>
  function modalBatal(id) {
    $("#batal-" + id).submit();
  }
</script>
@endsection