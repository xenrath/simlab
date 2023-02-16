@extends('layouts.app')

@section('title', 'Detail Pengambilan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('laboran/bahan') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Detail Pengambilan</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card border">
          <div class="card-header">
            <h4>Pengambilan</h4>
          </div>
          <div class="card-body">
            <div class="row p-0">
              <div class="col-md-6 p-0">
                <table class="table">
                  <tr>
                    <th class="w-25">Ruang / Lab</th>
                    <td class="w-50">{{ $pengambilan->ruang->nama }}</td>
                  </tr>
                  @if ($isadmin)
                  <tr>
                    <th class="w-25">Laboran</th>
                    <td class="w-50">{{ $pengambilan->ruang->laboran->nama }}</td>
                  </tr>
                  @endif
                </table>
              </div>
              <div class="col-md-6 p-0">
                <table class="table">
                  <tr>
                    <th class="w-25">Tanggal</th>
                    <td class="w-50">{{ date('d M Y', strtotime($pengambilan->created_at)) }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="card border">
          <div class="card-header">
            <h4>Data Bahan</h4>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Kode</th>
                    <th>Nama Bahan</th>
                    <th>Stok</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($detailpengambilans as $detailpengambilan)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $detailpengambilan->bahan->kode }}</td>
                    <td>{{ $detailpengambilan->bahan->nama }}</td>
                    <td>{{ $detailpengambilan->jumlah }} {{ $detailpengambilan->satuan->singkatan }}</td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center">- Data tidak ditemukan -</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection