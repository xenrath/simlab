@extends('layouts.app')

@section('title', 'Tambah Arsip')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('kalab/arsip') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Data Arsip</h1>
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
                    <p>
                        @foreach (session('error') as $error)
                            <span class="bullet"></span>&nbsp;{{ $error }}
                            <br>
                        @endforeach
                    </p>
                </div>
            </div>
        @endif
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Arsip</h4>
                </div>
                <form action="{{ url('kalab/arsip') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="nama">Jenis Arsip</label>
                            <select class="custom-select custom-select-sm" id="jenis" name="jenis">
                                <option value="">- Pilih -</option>
                                <option value="Kuesioner" {{ Request::get('jenis') == 'mahasiswa' ? 'selected' : '' }}>
                                    Kuesioner</option>
                                <option value="Kuesioner" {{ Request::get('jenis') == 'mahasiswa' ? 'selected' : '' }}>
                                    Kuesioner</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="file">File</label>
                            <input type="file" name="file" id="file" class="form-control"
                                accept="application/pdf">
                        </div>
                    </div>
                    <div class="card-footer float-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
