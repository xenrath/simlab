@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/barang') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Barang</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Barang</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Kode</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $barang->kode }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Nama</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $barang->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Ruang</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $barang->ruang->nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Jumlah Normal</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $barang->normal }} Pcs
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Jumlah Rusak</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $barang->rusak }} Pcs
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Keterangan</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $barang->keterangan ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="chocolat-parent">
                                @if ($barang->gambar != null)
                                    <a href="{{ asset('storage/uploads/' . $barang->gambar) }}" class="chocolat-image"
                                        title="{{ $barang->nama }}">
                                        <div data-crop-image="h-100">
                                            <img alt="image" src="{{ asset('storage/uploads/' . $barang->gambar) }}"
                                                class="rounded w-100">
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                                        title="{{ $barang->nama }}">
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
