@extends('layouts.app')

@section('title', 'Barang Rusak')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Barang Rusak</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Barang Rusak</h4>
      </div>
      <div class="card-body p-0">
        <div class="p-4">
          <form action="{{ url('kalab/barangrusak') }}" method="get" id="get-kategori">
            <div class="float-xs-right float-sm-right float-left mb-3">
              <div class="input-group">
                <input type="search" class="form-control" name="keyword" placeholder="Cari"
                  value="{{ Request::get('keyword') }}" autocomplete="off" onsubmit="event.preventDefault();
                document.getElementById('get-keyword').submit();">
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
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Jumlah Rusak</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody id="data-barang">
              @forelse ($rusaks as $key => $rusak)
              <tr>
                <td class="text-center">{{ $rusaks->firstItem() + $key }}</td>
                <td>{{ $rusak->barang->kode }}</td>
                <td>{{ $rusak->barang->nama }}</td>
                <td>{{ $rusak->rusak }} {{ $rusak->satuan->singkatan }}</td>
                <td>{{ date('d M Y', strtotime($rusak->created_at)) }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">- Data tidak ditemukan -</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          <div class="pagination">
            {{ $rusaks->appends(Request::all())->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
@endsection