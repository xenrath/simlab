@extends('layouts.app')

@section('title', 'Tambah Tempat')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/tempat') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Tempat</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">Error!</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="px-3 m-0">
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
                    <h4>Tambah Tempat</h4>
                </div>
                <form action="{{ url('dev/tempat') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="kode">Kode</label>
                            <input type="text" name="kode" id="kode" class="form-control"
                                value="{{ old('kode') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama Tempat</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}">
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
