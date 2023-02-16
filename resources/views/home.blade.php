@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@if (auth()->user()->isAdmin())
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  @php
  $user = auth()->user();
  @endphp
  @if ($user->telp == null || $user->alamat == null)
  <div class="hero bg-primary text-white">
    <div class="hero-inner">
      <h2>Selamat Datang, {{ ucfirst(auth()->user()->nama) }}!</h2>
      <p class="lead">Untuk keperluan Anda, lengkapi data diri Anda terlebih dahulu.</p>
      <div class="mt-4">
        <a href="{{ url('profile/' . auth()->user()->id) }}" class="btn btn-outline-white btn-lg btn-icon icon-left">
          <i class="far fa-user"></i> Lengkapi Data
        </a>
      </div>
    </div>
  </div>
  @endif
  <div class="section-body">
    <h2 class="section-title">Jumlah Calon Peminjam Yg Menunggu Konfirmasi</h2>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <a href="">
              <div class="card-header">
                <h4>Nama</h4>
              </div>
            </a>
            <div class="card-body">
              10
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

@if(Auth::user()->isPeminjam())
<section class="section">
  <div class="section-header">
    <h1>Dashboard Peminjam</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data barang yang dipinjam</h4>
          </div>
          <div class="card-body">
            <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
              <li class="nav-item border rounded">
                <a class="nav-link active" id="menunggu-tab" data-toggle="tab" href="#menunggu" role="tab"
                  aria-controls="menunggu" aria-selected="true">
                  <i class="fas fa-clock d-inline d-md-none"></i>
                  <span class="font-weight-bold d-none d-md-inline">Menunggu</span>
                </a>
              </li>
              <li class="nav-item border rounded mx-2">
                <a class="nav-link" id="diterima-tab" data-toggle="tab" href="#diterima" role="tab"
                  aria-controls="diterima" aria-selected="false">
                  <i class="fas fa-check-circle d-inline d-md-none"></i>
                  <span class="font-weight-bold d-none d-md-inline">Diterima</span>
                </a>
              </li>
              <li class="nav-item border rounded">
                <a class="nav-link" id="selesai-tab" data-toggle="tab" href="#selesai" role="tab"
                  aria-controls="selesai" aria-selected="false">
                  <i class="fas fa-clipboard-list d-inline d-md-none"></i>
                  <span class="font-weight-bold d-none d-md-inline">Berakhir</span>
                </a>
              </li>
            </ul>
            <div class="tab-content w-100 mt-3" id="myTabContent">
              <div class="tab-pane fade show active" id="menunggu" role="tabpanel" aria-labelledby="menunggu-tab">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-center">No.</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Ruang (Lab)</th>
                        <th class="text-center">Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($menunggus as $menunggu)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $menunggu->jam_awal }}, {{ date('d-m-Y', strtotime($menunggu->tanggal_awal)) }}</td>
                        <td>{{ $menunggu->jam_akhir }}, {{ date('d-m-Y', strtotime($menunggu->tanggal_akhir)) }}</td>
                        <td>{{ $menunggu->ruang->nama }}</td>
                        <td class="text-center">
                          <a href="{{ url('pinjam/' . $menunggu->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                            <span class="d-none d-md-inline">Detail</span>
                          </a>
                          <a href="{{ url('pinjam/' . $menunggu->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-print"></i>
                            <span class="d-none d-md-inline">Cetak</span>
                          </a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="diterima" role="tabpanel" aria-labelledby="diterima-tab">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-center">No.</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Ruang (Lab)</th>
                        <th class="text-center">Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($diterimas as $diterima)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $diterima->jam_awal }}, {{ date('d-m-Y', strtotime($diterima->tanggal_awal)) }}</td>
                        <td>{{ $diterima->jam_akhir }}, {{ date('d-m-Y', strtotime($diterima->tanggal_akhir)) }}</td>
                        <td>{{ $diterima->ruang->nama }}</td>
                        <td class="text-center">
                          button
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai-tab">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="text-center">No.</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Ruang (Lab)</th>
                        <th class="text-center">Opsi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($selesais as $selesai)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $selesai->jam_awal }}, {{ date('d-m-Y', strtotime($selesai->tanggal_awal)) }}</td>
                        <td>{{ $selesai->jam_akhir }}, {{ date('d-m-Y', strtotime($selesai->tanggal_akhir)) }}</td>
                        <td>{{ $selesai->ruang->nama }}</td>
                        <td class="text-center">
                          button
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

@if (auth()->user()->isLaboran())
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  @if (auth()->user()->telp == null)
  <div class="hero bg-primary text-white">
    <div class="hero-inner">
      <h2>Selamat Datang, {{ ucfirst(auth()->user()->nama) }}!</h2>
      <p class="lead">Untuk keperluan Anda, lengkapi data diri Anda terlebih dahulu.</p>
      <div class="mt-4">
        <a href="{{ url('profile/' . auth()->user()->id) }}" class="btn btn-outline-white btn-lg btn-icon icon-left">
          <i class="far fa-user"></i> Lengkapi Data
        </a>
      </div>
    </div>
  </div>
  @endif
  <div class="section-body">
    <h2 class="section-title">Data Peminjaman</h2>
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-clock"></i>
          </div>
          <div class="card-wrap">
            <a href="">
              <div class="card-header">
                <h4>Menunggu</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($menunggus) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-clock"></i>
          </div>
          <div class="card-wrap">
            <a href="">
              <div class="card-header">
                <h4>Diterima</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($diterimas) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-clock"></i>
          </div>
          <div class="card-wrap">
            <a href="">
              <div class="card-header">
                <h4>Selesai</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($selesais) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif

{{-- @if(Auth::user()->isPerawat())
<section class="section">
  <div class="section-header">
    <h1>Dashboard Perawat</h1>
  </div>
  <div class="section-body">
    <h2 class="section-title">Jumlah Calon Peminjam Yg Menunggu Konfirmasi</h2>
    <div class="row">
      <?php $ruangs = \App\Models\Ruang::where('role', 'perawat')->get(); ?>
      @foreach ($ruangs as $ruang)
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <a href="{{url('pinjam-perawat')}}/{{ $ruang->id }}">
              <div class="card-header">
                <h4>{{ $ruang->nama }}</h4>
              </div>
            </a>
            <div class="card-body">
              <?php $jumlah_peminjam = \App\Models\Pinjam::where('ruang_id', $ruang->id)->where('status', 0)->count(); ?>
              {{ $jumlah_peminjam }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div class="section-body">
    <h2 class="section-title">Jumlah Peminjam Yg Belum dikembalikan</h2>
    <div class="row">
      @foreach ($ruangs as $ruang)
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-warning">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <a href="{{url('kembali-perawat')}}/{{ $ruang->id }}">
              <div class="card-header">
                <h4>{{ $ruang->nama }}</h4>
              </div>
            </a>
            <div class="card-body">
              <?php $jumlah_peminjam = \App\Models\Pinjam::where('ruang_id', $ruang->id)->where('status_peminjaman', 1)->count(); ?>
              {{ $jumlah_peminjam }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if(Auth::user()->isK3())
<section class="section">
  <div class="section-header">
    <h1>Dashboard K3</h1>
  </div>
  <div class="section-body">
    <h2 class="section-title">Jumlah Calon Peminjam Yg Menunggu Konfirmasi</h2>
    <div class="row">
      <?php $ruangs = \App\Models\Ruang::where('role', 'k3')->get(); ?>
      @foreach ($ruangs as $ruang)
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <a href="{{url('pinjam-k3')}}/{{ $ruang->id }}">
              <div class="card-header">
                <h4>{{ $ruang->nama }}</h4>
              </div>
            </a>
            <div class="card-body">
              <?php $jumlah_peminjam = \App\Models\Pinjam::where('ruang_id', $ruang->id)->where('status', 0)->count(); ?>
              {{ $jumlah_peminjam }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div class="section-body">
    <h2 class="section-title">Jumlah Peminjam Yg Belum dikembalikan</h2>
    <div class="row">
      @foreach ($ruangs as $ruang)
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-warning">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <a href="{{url('kembali-k3')}}/{{ $ruang->id }}">
              <div class="card-header">
                <h4>{{ $ruang->nama }}</h4>
              </div>
            </a>
            <div class="card-body">
              <?php $jumlah_peminjam = \App\Models\Pinjam::where('ruang_id', $ruang->id)->where('status_peminjaman', 1)->count(); ?>
              {{ $jumlah_peminjam }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if(Auth::user()->isFarmasi())
<section class="section">
  <div class="section-header">
    <h1>Dashboard Farmasi</h1>
  </div>
  <div class="section-body">
    <h2 class="section-title">Jumlah Calon Peminjam Yg Menunggu Konfirmasi</h2>
    <div class="row">
      <?php $ruangs = \App\Models\Ruang::where('role', 'farmasi')->get(); ?>
      @foreach ($ruangs as $ruang)
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <a href="{{url('pinjam-farmasi')}}/{{ $ruang->id }}">
              <div class="card-header">
                <h4>{{ $ruang->nama }}</h4>
              </div>
            </a>
            <div class="card-body">
              <?php $jumlah_peminjam = \App\Models\Pinjam::where('ruang_id', $ruang->id)->where('status', 0)->count(); ?>
              {{ $jumlah_peminjam }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div class="section-body">
    <h2 class="section-title">Jumlah Peminjam Yg Belum dikembalikan</h2>
    <div class="row">
      @foreach ($ruangs as $ruang)
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-warning">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <a href="{{url('kembali-farmasi')}}/{{ $ruang->id }}">
              <div class="card-header">
                <h4>{{ $ruang->nama }}</h4>
              </div>
            </a>
            <div class="card-body">
              <?php $jumlah_peminjam = \App\Models\Pinjam::where('ruang_id', $ruang->id)->where('status_peminjaman', 1)->count(); ?>
              {{ $jumlah_peminjam }}
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif --}}
@endsection