@extends('layouts.app')

@section('title', 'Detail Peminjam')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/peminjam') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Peminjam</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail Peminjam</h4>
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
                <td>
                  @if ($user->telp)
                  +62{{ $user->telp }}
                  @else
                  -
                  @endif
                </td>
              </tr>
              <tr>
                <th>Alamat</th>
                <td>
                  @if ($user->alamat)
                  {{ $user->alamat }}
                  @else
                  -
                  @endif
                </td>
              </tr>
              <tr>
                <th>Prodi</th>
                <td>{{ $user->subprodi->jenjang }} {{ $user->subprodi->nama }}</td>
              </tr>
              <tr>
                <th>Semester</th>
                <td>{{ $user->semester }}</td>
              </tr>
            </table>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8 col-8">
            <div class="chocolat-parent">
              @if ($user->foto != null)
              <a href="{{ asset('storage/uploads/' . $user->foto) }}" class="chocolat-image" title="{{ $user->nama }}">
                <div data-crop-image="h-100">
                  <img alt="image" src="{{ asset('storage/uploads/' . $user->foto) }}" class="rounded w-100">
                </div>
              </a>
              @else
              <a href="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="chocolat-image"
                title="{{ $user->nama }}">
                <div data-crop-image="h-100">
                  <img alt="image" src="{{ asset('storage/uploads/logo-bhamada1.png') }}" class="rounded w-100">
                </div>
              </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection