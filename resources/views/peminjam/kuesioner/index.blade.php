@extends('layouts.app')

@section('title', 'Tata Cara')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Kuesioner</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <article class="article">
          <div class="article-header">
            <div class="article-image" data-background="{{ asset('storage/uploads/logo-bhamada1.png') }}">
            </div>
            <div class="article-title">
              <h2></h2>
            </div>
          </div>
          <div class="article-details">
            <p>Kuesioner Utility (Kepuasan) Mahasiswa Terhadap Pelayanan Praktikum.</p>
            <div class="article-cta">
              <a href="https://docs.google.com/forms/d/e/1FAIpQLScLcwV8EKYz3tk9p_D_7MWXf9lLEnl2wsZeuZlr_ENUna0C5A/viewform" target="_blank"
                class="btn btn-primary">Isi Kuesioner</a>
            </div>
          </div>
        </article>
      </div>
      <div class="col-12 col-sm-6 col-md-6 col-lg-3">
        <article class="article">
          <div class="article-header">
            <div class="article-image" data-background="{{ asset('storage/uploads/logo-bhamada1.png') }}">
            </div>
            <div class="article-title">
              <h2></h2>
            </div>
          </div>
          <div class="article-details">
            <p>Kuesioner Kepuasan Sarana Prasarana Pelayanan Laboratorium Universitas Bhamada Slawi.</p>
            <div class="article-cta">
              <a href="https://docs.google.com/forms/d/e/1FAIpQLSc3BrRkLpbw5oOe6nkEqP-Jvtbt3q20pKPUDhvxZ9BcQJMBtg/viewform" target="_blank"
                class="btn btn-primary">Isi Kuesioner</a>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
<script>
  function modalBatal(id) {
    $("#batal-" + id).submit();
  }
</script>
@endsection