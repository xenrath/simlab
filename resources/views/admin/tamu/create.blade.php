@extends('layouts.app')

@section('title', 'Tambah Tamu')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/tamu') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Tamu</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Tambah Tamu</h4>
                </div>
                <form action="{{ url('admin/tamu') }}" method="POST" autocomplete="off" id="form-submit">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="nama">Nama Tamu</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control rounded-0 @error('nama') is-invalid @enderror"
                                onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))"
                                value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="institusi">
                                Asal Institusi
                                <small class="text-muted">(contoh: Universitas ABC, Rumah Sakit ABC)</small>
                            </label>
                            <input type="text" name="institusi" id="institusi"
                                class="form-control rounded-0 @error('institusi') is-invalid @enderror"
                                value="{{ old('institusi') }}">
                            @error('institusi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="telp">Nomor Telepon
                                <small class="text-muted">(contoh: 081234567890)</small>
                            </label>
                            <input type="tel" class="form-control rounded-0 @error('telp') is-invalid @enderror"
                                name="telp" id="telp" value="{{ old('telp') }}"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            @error('telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="alamat">Alamat
                                <small class="text-muted">(opsional)</small>
                            </label>
                            <textarea class="form-control rounded-0" name="alamat" id="alamat" cols="30" rows="10"
                                style="height: 80px">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                            <div id="btn-submit-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-submit-text">Tambah Tamu</span>
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
