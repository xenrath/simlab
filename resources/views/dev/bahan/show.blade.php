@extends('layouts.app')

@section('title', 'Detail Bahan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/bahan') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Bahan</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Bahan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Kode</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $bahan->kode }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Nama</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $bahan->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Ruang</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $bahan->ruang->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Stok</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $bahan->stok }} {{ $bahan->satuan->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Keterangan</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $bahan->keterangan ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="chocolat-parent">
                                @if ($bahan->gambar != null)
                                    <a href="{{ asset('storage/uploads/' . $bahan->gambar) }}" class="chocolat-image"
                                        title="{{ $bahan->nama }}">
                                        <div data-crop-image="h-100">
                                            <img alt="image" src="{{ asset('storage/uploads/' . $bahan->gambar) }}"
                                                class="rounded w-100">
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                                        title="{{ $bahan->nama }}">
                                        <div data-crop-image="h-100">
                                            <img alt="image" src="{{ asset('storage/uploads/logo-bhamada1.png') }}"
                                                class="rounded w-100">
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
