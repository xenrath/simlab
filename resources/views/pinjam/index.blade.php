@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Pinjam Barang</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Pilih Lab. Prodi</h4>
          </div>
          <div class="card-body">
            <table class="table table-hover" id="table-1">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama Prodi</th>
                  <th class="text-center">Opsi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($prodis as $prodi)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $prodi->nama }}</td>
                  <td class="text-center w-25">
                    <a href="{{ url('peminjam/pinjam/' . lcfirst($prodi->nama)) }}" class="btn btn-sm btn-primary">
                      <i class="fas fa-check d-md-none"></i>
                      <span class="d-none d-md-inline">Pilih</span>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection