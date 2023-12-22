@extends('layouts.app')

@section('title', 'Tambah Laboran')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/pengguna/laboran') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Laboran</h1>
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
                    <h4>Tambah Laboran</h4>
                </div>
                <form action="{{ url('admin/pengguna/laboran') }}" method="POST" autocomplete="off"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kode">Username</label>
                            <input type="text" name="kode" id="kode" class="form-control"
                                value="{{ old('kode') }}">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}">
                        </div>
                        <div class="form-group">
                            <label for="prodi_id">Prodi</label>
                            <select class="form-control selectric" id="prodi_id" name="prodi_id">
                                <option value="">Pilih Prodi</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->singkatan) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="telp">
                                No. Telepon
                                <small>(opsional)</small>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">+62</div>
                                </div>
                                <input type="text" class="form-control" name="telp" id="telp"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    value="{{ old('telp') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                Alamat
                                <small>(opsional)</small>
                            </label>
                            <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="10" style="height: 80px">{{ old('alamat') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto">
                                Foto
                                <small>(opsional)</small>
                            </label>
                            <input type="file" name="foto" id="foto" class="form-control"
                                aria-describedby="foto-help" accept="image/*">
                            <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin
                                diubah.</small>
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
    <script>
        var role = document.getElementById('role');
        var layout_subprodi = document.getElementById('layout_subprodi');

        if (role.value == 'peminjam') {
            layout_subprodi.style.display = 'inline';
        } else {
            layout_subprodi.style.display = 'none';
        }

        function getrole() {
            if (role.value == 'peminjam') {
                layout_subprodi.style.display = 'inline';
            } else {
                layout_subprodi.style.display = 'none';
            }
        }
    </script>
@endsection
