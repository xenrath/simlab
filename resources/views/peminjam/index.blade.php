@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  @if (auth()->user()->telp == null || auth()->user()->alamat == null)
  <div class="mb-4">
    <div class="hero bg-primary text-white">
      <div class="hero-inner">
        <h2>Selamat Datang, {{ ucfirst(auth()->user()->nama) }}!</h2>
        <p class="lead">Untuk keperluan Anda, lengkapi data diri Anda terlebih dahulu.</p>
        <div class="mt-4">
          <a href="{{ url('profile') }}" class="btn btn-outline-white btn-lg btn-icon icon-left">
            <i class="far fa-user"></i> Lengkapi Data
          </a>
        </div>
      </div>
    </div>
  </div>
  @endif
  <div class="section-body">
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-cog"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('peminjam/peminjaman') }}">
              <div class="card-header">
                <h4>Peminjaman Menunggu</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($peminjaman) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-cog"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('peminjam/pengembalian') }}">
              <div class="card-header">
                <h4>Dalam Peminjaman</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($pengembalian) }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="fas fa-cog"></i>
          </div>
          <div class="card-wrap">
            <a href="{{ url('peminjam/riwayat') }}">
              <div class="card-header">
                <h4>Riwayat Pinjaman</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($riwayat) }}
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Barang</h4>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <form action="{{ url('peminjam') }}" method="get" id="get-barang">
                <div class="float-xs-right float-sm-right float-left mb-3">
                  <div class="input-group">
                    <input type="search" class="form-control" name="keybarang" placeholder="Cari"
                      value="{{ Request::get('keybarang') }}" autocomplete="off" onsubmit="event.preventDefault();
                    document.getElementById('get-barang').submit();">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th class="text-center">Opsi</th>
                  </tr>
                </thead>
                <tbody id="data-barang">
                  @forelse ($barangs as $key => $barang)
                  <tr>
                    <td class="text-center">{{ $barangs->firstItem() + $key }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td>{{ $barang->stok }} {{ ucfirst($barang->satuan) }}</td>
                    <td class="text-center w-25">
                      <a href="{{ url('peminjam/barang/detail/' . $barang->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i>
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="pagination">
              {{ $barangs->appends(Request::all())->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Bahan</h4>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <form action="{{ url('peminjam') }}" method="get" id="get-bahan">
                <div class="float-xs-right float-sm-right float-left mb-3">
                  <div class="input-group">
                    <input type="search" class="form-control" name="keybahan" placeholder="cari bahan.."
                      value="{{ Request::get('keybahan') }}" autocomplete="off" onsubmit="event.preventDefault();
                    document.getElementById('get-bahan').submit();">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Nama Bahan</th>
                    <th>Stok</th>
                    <th class="text-center">Opsi</th>
                  </tr>
                </thead>
                <tbody id="data-barang">
                  @forelse ($bahans as $key => $bahan)
                  <tr>
                    <td class="text-center">{{ $bahans->firstItem() + $key }}</td>
                    <td>{{ $bahan->nama }}</td>
                    <td>
                      {{ $bahan->stok / 1000 }}
                      @if ($bahan->satuan == "mililiter")
                      Liter
                      @else
                      Gram
                      @endif
                    </td>
                    <td class="text-center w-25">
                      <a href="{{ url('peminjam/barang/detail/' . $bahan->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i>
                      </a>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="pagination">
              {{ $bahans->appends(Request::all())->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div> --}}
  </div>
</section>
{{-- <script>
  $(document).ready(function() {
  $('select[name="kategori"]').on('change', function() {
    $value = $(this).val();
    $.ajax({
      url: "{{ url('peminjam' ) }}",
      type: "GET",
      data: { "kategori": $value },
      success: function(data) {
        // $('#data-barang').html(data);
        console.log(data);
      },
    });
  });
});
</script> --}}
@endsection
{{-- <h2 class="section-title">Data barang yang tersedia.</h2>
<div class="row">
  <div class="col-md-6">
  </div>
  <div class="col-md-6">
    <ul class="nav nav-pills float-right mt-3" id="myTab3" role="tablist">
      <li class="nav-item border rounded mr-2">
        <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab" aria-controls="home"
          aria-selected="true">Barang</a>
      </li>
      <li class="nav-item border rounded">
        <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile"
          aria-selected="false">Bahan</a>
      </li>
    </ul>
  </div>
</div>
<div class="row">
  <div class="col-12 col-sm-6 col-md-6 col-lg-3">
    <img src="{{ asset('stisla/assets/img/kebidanan.jpeg') }}" alt="Kebidanan" class="w-100 rounded">
    <h4 class="text-center mt-2">Kebidanan</h4>
  </div>
  <div class="col-12 col-sm-6 col-md-6 col-lg-3">
    <img src="{{ asset('stisla/assets/img/keperawatan.jpeg') }}" alt="Keperawatan" class="w-100 rounded">
    <h4 class="text-center mt-2">Keperawatan</h4>
  </div>
  <div class="col-12 col-sm-6 col-md-6 col-lg-3">
    <img src="{{ asset('stisla/assets/img/farmasi.jpeg') }}" alt="Farmasi" class="w-100 rounded">
    <h4 class="text-center mt-2">Farmasi</h4>
  </div>
  <div class="col-12 col-sm-6 col-md-6 col-lg-3">
    <img src="{{ asset('stisla/assets/img/k3.jpg') }}" alt="K3" class="w-100 rounded">
    <h4 class="text-center mt-2">K3</h4>
  </div>
</div> --}}