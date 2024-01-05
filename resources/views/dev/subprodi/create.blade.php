@extends('layouts.app')

@section('title', 'Tambah Subprodi')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/subprodi') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Subprodi</h1>
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
                    <h4>Tambah Subprodi</h4>
                </div>
                <form action="{{ url('dev/subprodi') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="prodi_id">Main Prodi</label>
                            <select class="custom-select custom-select-sm" name="prodi_id" id="prodi_id">
                                <option value="" {{ old('prodi_id') == '' ? 'selected' : '' }}>- Pilih -
                                </option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->singkatan) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="jenjang">Jenjang</label>
                            <select class="custom-select custom-select-sm" name="jenjang" id="jenjang">
                                <option value="" {{ old('jenjang') == '' ? 'selected' : '' }}>- Pilih -
                                </option>
                                <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="D4" {{ old('jenjang') == 'D4' ? 'selected' : '' }}>D4</option>
                                <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="Profesi" {{ old('jenjang') == 'Profesi' ? 'selected' : '' }}>Profesi
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama Prodi</label>
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
