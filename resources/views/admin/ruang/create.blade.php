@extends('layouts.app')

@section('title', 'Tambah Ruang')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/ruang') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Ruang</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Ruang</h4>
                </div>
                <form action="{{ url('admin/ruang') }}" method="POST" autocomplete="off" id="form-submit">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="nama">Nama Ruang</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control rounded-0 @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tempat_id">Tempat</label>
                            <select class="form-control rounded-0 @error('tempat_id') is-invalid @enderror" id="tempat_id"
                                name="tempat_id">
                                <option value="">- Pilih -</option>
                                @foreach ($tempats as $tempat)
                                    <option value="{{ $tempat->id }}"
                                        {{ old('tempat_id') == $tempat->id ? 'selected' : '' }}>
                                        {{ $tempat->nama }}</option>
                                @endforeach
                            </select>
                            @error('tempat_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="prodi_id">Prodi</label>
                            <select class="form-control rounded-0 @error('prodi_id') is-invalid @enderror" id="prodi_id"
                                name="prodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->singkatan) }}</option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="is_praktik">Untuk Praktik</label>
                            <select class="form-control rounded-0" id="is_praktik" name="is_praktik"
                                onchange="is_praktik_set()">
                                <option value="1" {{ old('is_praktik') == '1' ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('is_praktik') == '0' ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>
                        <div class="form-group mb-2" id="layout-laboran">
                            <label for="laboran_id">Laboran</label>
                            <select class="form-control rounded-0 @error('laboran_id') is-invalid @enderror" id="laboran_id"
                                name="laboran_id">
                                <option value="">- Pilih -</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('laboran_id') == $user->id ? 'selected' : '' }}>
                                        {{ ucfirst($user->prodi->singkatan) }} - {{ $user->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('laboran_id')
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
        $(document).ready(function() {
            is_praktik_set();
        });

        function is_praktik_set() {
            let val = $("#is_praktik").val();
            if (val === "1") {
                $("#layout-laboran").show();
            } else {
                $("#layout-laboran").hide();
                $("#laboran_id").val("");
            }
        }
    </script>
    <script>
        function form_submit() {
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit-text').hide();
            $('#btn-submit-load').show();
            $('#form-submit').submit();
        }
    </script>
@endsection
