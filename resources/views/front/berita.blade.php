@extends('front.main')

@section('content')
  <a href="{{ asset('storage/uploads/' . $berita->gambar) }}" class="galelry-lightbox">
    <section class="img-berita"
      style="width: 100%; height: 100vh; background: url({{ asset('storage/uploads/' . $berita->gambar) }}) center center; background-size: cover;">
    </section>
  </a>
  <div class="container">
    <p class="mt-3 text-end">{{ date('d M Y', strtotime($berita->created_at)) }}</p>
  </div>
  <section class="inner-page">
    <div class="container">
      <div class="text-berita">
        <h4>{{ $berita->judul }}</h4>
        {!! $berita->isi !!}
      </div>
    </div>
  </section>
@endsection
