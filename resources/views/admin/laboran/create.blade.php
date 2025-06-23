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
                <form action="{{ url('admin/laboran') }}" method="POST" autocomplete="off" enctype="multipart/form-data"
                    id="form-submit">
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
                                        {{ ucfirst($prodi->singkatan) }}
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
                            <label for="telp">
                                No. Telepon
                                <small class="text-muted">(opsional)</small>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text rounded-0">+62</div>
                                </div>
                                <input type="tel" class="form-control rounded-0 @error('telp') is-invalid @enderror"
                                    name="telp" id="telp"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                    value="{{ old('telp') }}">
                                @error('telp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="alamat">
                                Alamat
                                <small class="text-muted">(opsional)</small>
                            </label>
                            <textarea class="form-control rounded-0" name="alamat" id="alamat" cols="30" rows="10"
                                style="height: 80px">{{ old('alamat') }}</textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="foto">
                                Foto
                                <small class="text-muted">(opsional)</small>
                            </label>
                            <input type="file" name="foto" id="foto"
                                class="form-control rounded-0 @error('foto') is-invalid @enderror"
                                aria-describedby="foto-help" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                            <div id="btn-submit-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-submit-text">Tambah Laboran</span>
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
