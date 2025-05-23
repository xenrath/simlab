@extends('layouts.app')

@section('title', 'Buat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Buat Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Peminjaman</h4>
                </div>
                <form action="{{ url('peminjam/farmasi/buat/create') }}" method="get">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Kategori Praktik</label>
                            <select name="kategori" id="kategori"
                                class="custom-select custom-select-sm rounded-0 @error('kategori') is-invalid @enderror">
                                <option value="">- Pilih -</option>
                                <option value="estafet" {{ old('kategori') == 'estafet' ? 'selected' : null }}>Estafet
                                </option>
                                <option value="mandiri" {{ old('kategori') == 'mandiri' ? 'selected' : null }}>Mandiri
                                </option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>Ruang Lab</label>
                            <select name="ruang_id" id="ruang_id"
                                class="custom-select custom-select-sm rounded-0 @error('ruang_id') is-invalid @enderror">
                                <option value="">- Pilih -</option>
                                @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}"
                                        {{ old('ruang_id') == $ruang->id ? 'selected' : null }}>
                                        {{ $ruang->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ruang_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="submit" class="btn btn-primary rounded-0">Selanjutnya</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
