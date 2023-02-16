@extends('layouts.app')

@section('title', 'Bahan Habis')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Bahan Habis</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Bahan Habis</h4>
      </div>
      <div class="card-body p-0">
        <div class="p-4">
          <form action="{{ url('kalab/bahan/habis') }}" method="get" id="get-kategori">
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
                <th>Nama Bahan</th>
                <th>Tempat</th>
                <th class="text-center">Opsi</th>
              </tr>
            </thead>
            <tbody id="data-barang">
              @forelse ($bahan_habises as $key => $bahan)
              <tr>
                <td class="text-center">{{ $bahan_habises->firstItem() + $key }}</td>
                <td>{{ $bahan->nama }}</td>
                <td>{{ $bahan->ruang->tempat->nama }} {{ $bahan->ruang->nama }}</td>
                <td class="text-center">
                  <a href="{{ url('kalab/habis/detail/' . $bahan->id) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i>
                    <span class="d-none d-lg-inline">Detail</span>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center">- Data tidak ditemukan -</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          <div class="pagination">
            {{ $bahan_habises->appends(Request::all())->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
@endsection