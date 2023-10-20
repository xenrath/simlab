@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/uploads/asset/peminjaman-buat.svg') }}" alt=""
                                style="height: 180px;">
                        </div>
                        <div class="card-footer bg-whitesmoke text-center">
                            <a href="{{ url('admin/peminjaman/create') }}" class="btn btn-info">Buat Peminjaman</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/uploads/asset/peminjaman-proses.svg') }}" alt=""
                                style="height: 180px;">
                        </div>
                        <div class="card-footer bg-whitesmoke text-center">
                            <a href="{{ url('admin/peminjaman/proses') }}" class="btn btn-info">Dalam Peminjaman</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/uploads/asset/peminjaman-selesai.svg') }}" alt=""
                                style="height: 180px;">
                        </div>
                        <div class="card-footer bg-whitesmoke text-center">
                            <a href="{{ url('admin/peminjaman/selesai') }}" class="btn btn-info">Peminjaman Selesai</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ asset('storage/uploads/asset/peminjaman-tagihan.svg') }}" alt=""
                                style="height: 180px;">
                        </div>
                        <div class="card-footer bg-whitesmoke text-center">
                            <a href="{{ url('admin/peminjaman/tagihan') }}" class="btn btn-info">Lihat Tagihan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
