@extends('layouts.app')

@section('title', 'Tambah Laboran')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/laboran') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Laboran</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Tambah Laboran</h4>
                </div>
                <form action="{{ url('admin/laboran') }}" method="POST" autocomplete="off" id="form-submit">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="kode">Username</label>
                            <input type="text" name="kode" id="kode"
                                class="form-control rounded-0 @error('kode') is-invalid @enderror"
                                value="{{ old('kode') }}">
                            @error('kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control rounded-0 @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="prodi_id">Prodi</label>
                            <select class="custom-select custom-select-sm rounded-0 @error('prodi_id') is-invalid @enderror"
                                id="prodi_id" name="prodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->nama) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="is_pengelola_bahan">Jadikan Pengelola Bahan</label>
                            <select class="custom-select custom-select-sm rounded-0" id="is_pengelola_bahan"
                                name="is_pengelola_bahan">
                                <option value="0" {{ old('is_pengelola_bahan') == 0 ? 'selected' : '' }}>
                                    Tidak</option>
                                <option value="1" {{ old('is_pengelola_bahan') == 1 ? 'selected' : '' }}>
                                    Ya</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                            <div id="btn-submit-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-submit-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function form_submit() {
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit-text').hide();
            $('#btn-submit-load').show();
            $('#form-submit').submit();
        }
    </script>
@endsection
