@extends('layouts.app')

@section('title', 'Detail Berita')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('web/berita') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Data Berita</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Detail Berita</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12">
              <p>
                <strong>Tanggal</strong> : {{ date('d M Y', strtotime($berita->created_at)) }}
              </p>
              <p>
                <strong>Judul</strong>
              </p>
              <p>{{ $berita->judul }}</p>
              <p>
                <strong>Isi</strong>
              </p>
              {!! $berita->isi !!}
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8 col-8">
              <div class="chocolat-parent">
                @if ($berita->gambar != null)
                  <a href="{{ asset('storage/uploads/' . $berita->gambar) }}" class="chocolat-image"
                    title="{{ $berita->nama }}">
                    <div data-crop-image="h-100">
                      <img alt="image" src="{{ asset('storage/uploads/' . $berita->gambar) }}"
                        class="img-fluid">
                    </div>
                  </a>
                @else
                  <a href="{{ asset('storage/uploads/logo-bhamada.png') }}" class="chocolat-image"
                    title="{{ $berita->nama }}">
                    <div data-crop-image="h-100">
                      <img alt="image" src="{{ asset('storage/uploads/logo-bhamada.png') }}"
                        class="img-fluid">
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
