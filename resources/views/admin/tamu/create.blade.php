@extends('layouts.app')

@section('title', 'Tambah Tamu')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/tamu') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Tamu</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">Gagal !</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="px-3 mb-0">
                        @foreach (session('error') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Tamu</h4>
                </div>
                <form action="{{ url('admin/tamu') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="nama">Nama Tamu</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))"
                                value="{{ old('nama') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="institusi">
                                Asal Institusi
                                <small>(contoh: Universitas ABC, Rumah Sakit ABC)</small>
                            </label>
                            <input type="text" name="institusi" id="institusi" class="form-control"
                                value="{{ old('institusi') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="telp">Nomor Telepon
                                <small>(contoh: 081234567890)</small>
                            </label>
                            <input type="tel" class="form-control" name="telp" id="telp"
                                value="{{ old('telp') }}"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">Alamat Tamu
                                <small>(opsional)</small>
                            </label>
                            <textarea name="alamat" id="alamat" cols="30" rows="10" class="form-control" style="height: 80px">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
