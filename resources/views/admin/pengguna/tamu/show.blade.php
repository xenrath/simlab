@extends('layouts.app')

@section('title', 'Detail Tamu')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/pengguna/tamu') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Tamu</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Tamu</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nama Tamu</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $tamu->nama }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Institusi</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $tamu->institusi }}
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>No. Telepon</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $tamu->telp }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Alamat</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $tamu->alamat }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
