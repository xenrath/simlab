@extends('layouts.app')

@section('title', 'Tambah Tamu')

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
        @if (session('error'))
            <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="alert-body">
                    <div class="alert-title">Error!</div>
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
                <form action="{{ url('admin/pengguna/tamu/' . $tamu->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Nama Tamu</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))"
                                value="{{ old('nama', $tamu->nama) }}">
                        </div>
                        <div class="form-group">
                            <label for="institusi">
                                Asal Institusi
                                <br>
                                <small>(contoh: Universitas ABC, Rumah Sakit ABC)</small>
                            </label>
                            <input type="text" name="institusi" id="institusi" class="form-control"
                                value="{{ old('institusi', $tamu->institusi) }}">
                        </div>
                        <div class="form-group">
                            <label for="telp">Nomor Telepon
                                <br>
                                <small>(contoh: 081234567890)</small>
                            </label>
                            <input type="tel" class="form-control" name="telp" id="telp"
                                value="{{ old('telp', $tamu->telp) }}"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Tamu
                                <small>(opsional)</small>
                            </label>
                            <textarea name="alamat" id="alamat" cols="30" rows="10" class="form-control" style="height: 80px">{{ old('alamat', $tamu->alamat) }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary mr-1">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
