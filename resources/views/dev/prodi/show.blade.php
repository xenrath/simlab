@extends('layouts.app')

@section('title', 'Detail Prodi')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/prodi') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Detail Prodi</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Prodi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Kode</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $prodi->kode }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Nama</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ ucfirst($prodi->nama) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Singkatan</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ ucfirst($prodi->singkatan) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Kategori</strong>
                                </div>
                                <div class="col-md-8">
                                    @if ($prodi->is_prodi)
                                        Prodi
                                    @else
                                        Bukan Prodi
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Tempat</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $prodi->tempat->nama }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
