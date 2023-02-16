@extends('layouts.app')

@section('title', 'Detail Ruang')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('kalab/ruang') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Ruang</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Detail Ruang</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12">
              <table class="table w-100">
                <tr>
                  <th>Kode</th>
                  <td>{{ $ruang->kode }}</td>
                </tr>
                <tr>
                  <th>Nama Ruang</th>
                  <td>{{ $ruang->nama }}</td>
                </tr>
                <tr>
                  <th>Tempat</th>
                  <td>{{ $ruang->tempat->nama }}</td>
                </tr>
                <tr>
                  <th>Lantai</th>
                  <td>
                    @if ($ruang->lantai == 'L1')
                      Lantai 1
                    @else
                      Lantai 2
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Prodi</th>
                  <td>
                    @if ($ruang->kode == '01')
                      - Keperawatan <br>
                      - Kebidanan <br>
                      - K3
                    @elseif ($ruang->kode == '02')
                      - Farmasi
                    @else
                      {{ ucfirst($ruang->prodi->nama) }}
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Laboran</th>
                  <td>{{ $ruang->laboran->nama }}</td>
                </tr>
              </table>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8 col-8">
              <div class="chocolat-parent">
                @if ($ruang->foto != null)
                  <a href="{{ asset('storage/uploads/' . $ruang->foto) }}" class="chocolat-image"
                    title="{{ $ruang->nama }}">
                    <div data-crop-image="h-100">
                      <img alt="image" src="{{ asset('storage/uploads/' . $ruang->foto) }}" class="rounded w-100">
                    </div>
                  </a>
                @else
                  <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                    title="{{ $ruang->nama }}">
                    <div data-crop-image="h-100">
                      <img alt="image" src="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="rounded w-100">
                    </div>
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
