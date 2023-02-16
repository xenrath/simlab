@extends('layouts.app')

@section('title', 'Detail Data User')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('user') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Detail Data User</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail User</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 col-12">
            <table class="table w-100">
              <tr>
                <th>Kode</th>
                <td>{{ $user->kode }}</td>
              </tr>
              <tr>
                <th>Nama</th>
                <td>{{ $user->nama }}</td>
              </tr>
              <tr>
                <th>No. Telepon</th>
                <td>{{ $user->telp }}</td>
              </tr>
              <tr>
                <th>Alamat</th>
                <td>{{ $user->alamat }}</td>
              </tr>
              <tr>
                <th>Role</th>
                <td>{{ $user->role }}</td>
              </tr>
            </table>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8 col-8">
            <img src="{{ asset('stisla/assets/img/logo-bhamada.png') }}" alt="" class="img-thumbnail rounded w-100">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection