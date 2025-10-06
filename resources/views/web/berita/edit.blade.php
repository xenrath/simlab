@extends('layouts.app')

@section('title', 'Tambah Berita')

@section('style')
    <link rel="stylesheet" href="{{ asset('stisla/node_modules/summernote/dist/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('web/berita') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Data Berita</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Tambah Berita</h4>
                </div>
                <form action="{{ url('web/berita/' . $berita->id) }}" method="POST" autocomplete="off"
                    enctype="multipart/form-data" id="form-submit">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="judul">Judul *</label>
                            <input type="text" name="judul" id="judul"
                                class="form-control rounded-0 @error('judul') is-invalid @enderror"
                                value="{{ old('judul', $berita->judul) }}">
                            @error('judul')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="gambar">Gambar (opsional)</label>
                            <input type="file" name="gambar" id="gambar"
                                class="form-control rounded-0 @error('gambar') is-invalid @enderror" accept="image/*">
                            @error('gambar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            @if ($berita->gambar)
                                <div class="text-right mt-2">
                                    <a href="{{ asset('storage/' . $berita->gambar) }}"
                                        class="btn btn-info btn-sm rounded-0">
                                        Lihat Gambar
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="form-group mb-2">
                            <label for="isi">Isi *</label>
                            <textarea class="summernote" name="isi" id="isi">{{ old('isi', $berita->isi) }}</textarea>
                            @error('isi')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                            <div id="btn-submit-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-submit-text">Simpan Berita</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset('stisla/node_modules/summernote/dist/summernote-bs4.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                toolbar: [
                    // pilih tools yang tidak menghasilkan inline style
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ],
                styleTags: false, // nonaktifkan dropdown heading dan styling bawaan
            });
        });
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
