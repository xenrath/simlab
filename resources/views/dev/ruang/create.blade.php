@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/ruang') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Ruang Lab</h1>
        </div>
        @if (session('status'))
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
                        @foreach (session('status') as $error)
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
                    <h4>Tambah Ruangan</h4>
                </div>
                <form action="{{ url('dev/ruang') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kode">Kode</label>
                            <input type="text" name="kode" id="kode" class="form-control"
                                value="{{ old('kode', $kode) }}">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Ruangan</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                value="{{ old('nama') }}">
                        </div>
                        <div class="form-group">
                            <label for="tempat_id">Tempat</label>
                            <select class="form-control selectric" id="tempat_id" name="tempat_id">
                                <option value="">- Pilih -</option>
                                @foreach ($tempats as $tempat)
                                    <option value="{{ $tempat->id }}"
                                        {{ old('tempat_id') == $tempat->id ? 'selected' : '' }}>{{ $tempat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lantai">Lantai</label>
                            <select class="form-control selectric" id="lantai" name="lantai">
                                <option value="">- Pilih Lantai -</option>
                                <option value="L1" {{ old('lantai') == 'L1' ? 'selected' : '' }}>Lantai 1</option>
                                <option value="L2" {{ old('lantai') == 'L2' ? 'selected' : '' }}>Lantai 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prodi_id">Prodi</label>
                            <select class="form-control selectric" id="prodi_id" name="prodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ ucfirst($prodi->singkatan) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="is_praktik">Untuk Praktik</label>
                            <select class="form-control selectric" id="is_praktik" name="is_praktik" onchange="praktik()">
                                <option value="1" {{ old('is_praktik') == '1' ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('is_praktik') == '0' ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>
                        <div id="layout_laboran_id">
                            <div class="form-group">
                                <label for="laboran_id">Laboran</label>
                                <select class="form-control select2" id="laboran_id" name="laboran_id">
                                    <option value="">- Pilih Laboran -</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('laboran_id') == $user->id ? 'selected' : '' }}>{{ $user->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary mr-1">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        praktik();

        function praktik() {
            if ($('#is_praktik').val() == 1) {
                $('#layout_laboran_id').show();
            } else {
                $('#layout_laboran_id').hide();
            }
        }
    </script>
@endsection
