@extends('layouts.app')

@section('title', 'Data Kuesioner')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Kuesioner</h1>
      <div class="section-header-button">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah</button>
      </div>
    </div>
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
          <div class="alert-title">Error!</div>
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          <p>
            @foreach (session('error') as $error)
              <span class="bullet"></span>&nbsp;{{ $error }}
            @endforeach
          </p>
        </div>
      </div>
    @endif
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Kuesioner</h4>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th class="text-center" width="120">No</th>
                      <th>Judul</th>
                      <th class="text-center" width="240">Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($kuesioners as $key => $kuesioner)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $kuesioner->judul }}</td>
                        <td class="text-center">
                          <a href="{{ url('kalab/kuesioner/' . $kuesioner->id . '/edit') }}" class="btn btn-warning">
                            <i class="fas fa-pen"></i> Edit
                          </a>
                          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus-{{ $kuesioner->id }}">
                            <i class="fas fa-trash"></i> Hapus
                          </button>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center">- Data tidak ditemukan -</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="modal fade" tabindex="-1" role="dialog" id="modal-tambah">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kuesioner</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ url('kalab/kuesioner') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="judul">Judul</label>
              <textarea class="form-control" id="judul" name="judul" cols="30" rows="10" style="height: 120px;"></textarea>
            </div>
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Tambah</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @foreach ($kuesioners as $kuesioner)
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus-{{ $kuesioner->id }}">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Hapus Kuesioner</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ url('kalab/kuesioner/' . $kuesioner->id) }}" method="post">
            @csrf
            @method('delete')
            <div class="modal-body">
              <p>Yakin hapus Kuesioner?</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection
