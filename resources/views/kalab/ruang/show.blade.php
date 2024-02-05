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
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Kode</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $ruang->kode }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Nama Ruang</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $ruang->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Tempat</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $ruang->tempat->nama }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Prodi</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ ucfirst($ruang->prodi->singkatan) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $ruang->laboran->nama ?? '-' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Untuk Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($ruang->is_praktik)
                                        <span class="badge badge-primary">Ya</span>
                                    @else
                                        <span class="badge badge-warning">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
