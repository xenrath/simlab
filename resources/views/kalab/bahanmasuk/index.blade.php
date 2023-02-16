@extends('layouts.app')

@section('title', 'Bahan Masuk')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Bahan Masuk</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Bahan Masuk</h4>
      </div>
      <div class="card-body p-0">
        <div class="p-4">
          <form action="{{ url('kalab/masuk/bahan') }}" method="get" id="get-filter">
            <div class="float-left mr-3">
              <div class="form-group">
                <label>Tanggal Awal</label>
                <input type="date" class="form-control" name="tanggal_awal" id="tanggal_awal"
                  value="{{ Request::get('tanggal_awal') }}">
              </div>
            </div>
            <div class="float-left mr-3">
              <div class="form-group">
                <label>Tanggal Akhir</label>
                <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir"
                  onchange="event.preventDefault(); document.getElementById('get-filter').submit();"
                  value="{{ Request::get('tanggal_akhir') }}">
              </div>
            </div>
            <div class="float-xs-right float-md-right float-left">
              <div class="form-group">
                <label>Cari</label>
                <div class="input-group">
                  <input type="search" class="form-control" name="keyword" placeholder="Cari"
                    value="{{ Request::get('keyword') }}" autocomplete="off" onsubmit="event.preventDefault();
                document.getElementById('get-filter').submit();">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
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
                <th>Jumlah</th>
                <th>Ruang</th>
                <th>Tanggal Masuk</th>
                <th class="text-center">Opsi</th>
              </tr>
            </thead>
            <tbody id="data-barang">
              @forelse ($stoks as $key => $stok)
              <tr>
                <td class="text-center">{{ $stoks->firstItem() + $key }}</td>
                <td>{{ $stok->bahan->nama }}</td>
                <td>{{ $stok->stok }} {{ $stok->satuan->singkatan }}</td>
                <td>{{ $stok->bahan->ruang->nama }}</td>
                <td>{{ date('d M Y', strtotime($stok->created_at)) }}</td>
                <td class="text-center">
                  <a href="{{ url('kalab/stokbahan/' . $stok->id) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i>
                    <span class="d-none d-lg-inline">Detail</span>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">- Data tidak ditemukan -</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer">
        <div class="pagination float-right">
          {{ $stoks->appends(Request::all())->links('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  var tanggalAwal = document.getElementById('tanggal_awal');
  var tanggalAkhir = document.getElementById('tanggal_akhir');
  if (tanggalAwal.value == "") {
    tanggalAkhir.readOnly = true;
  }
  tanggalAwal.addEventListener('change', function() {
    if (this.value == "") {
      tanggalAkhir.readOnly = true;
    } else {
      tanggalAkhir.readOnly = false;
    };
    tanggalAkhir.value = "";
    tanggalAkhir.setAttribute('min', this.value);
  });
</script>
@endsection