@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/mahasiswa') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Mahasiswa</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Tambah Mahasiswa</h4>
                </div>
                <form action="{{ url('admin/mahasiswa') }}" method="POST" autocomplete="off" enctype="multipart/form-data"
                    id="form-submit">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="kode">NIM</label>
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
                            <label for="subprodi_id">Prodi</label>
                            <select
                                class="custom-select custom-select-sm rounded-0 @error('subprodi_id') is-invalid @enderror"
                                name="subprodi_id" id="subprodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($subprodis as $subprodi)
                                    <option value="{{ $subprodi->id }}"
                                        {{ old('subprodi_id') == $subprodi->id ? 'selected' : '' }}>
                                        {{ $subprodi->jenjang }} {{ $subprodi->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subprodi_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="tingkat">Tingkat</label>
                            <select class="custom-select custom-select-sm rounded-0 @error('tingkat') is-invalid @enderror"
                                name="tingkat" id="tingkat">
                                <option value="">- Pilih -</option>
                                <option value="1" {{ old('tingkat') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ old('tingkat') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ old('tingkat') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ old('tingkat') == '4' ? 'selected' : '' }}>4</option>
                            </select>
                            @error('tingkat')
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
                                <input type="text" class="form-control rounded-0 @error('telp') is-invalid @enderror"
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
                            <span id="btn-submit-text">Tambah Mahasiswa</span>
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
