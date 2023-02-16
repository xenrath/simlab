@extends('layouts.app')

@section('title', 'Data Bahan')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Data Bahan</h1>
    <div class="section-header-button">
      <a href="{{ url('admin/bahan/create') }}" class="btn btn-primary">Tambah</a>
    </div>
  </div>
  @if (session('failures'))
  <div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <div class="alert-title">Error</div>
    </div>
    @foreach (session('failures') as $fail)
    <p>
      <span class="bullet"></span>&nbsp;
      Baris ke {{ $fail->row() }} : <strong>{{ $fail->values()[$fail->attribute()] }}</strong>,
      @foreach ($fail->errors() as $error)
      {{ $error }}
      @endforeach
    </p>
    @endforeach
  </div>
  @endif
  @if (session('error'))
  <div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <div class="alert-title">Error</div>
    </div>
    @foreach (session('error') as $error)
    <p>
      <span class="bullet"></span>&nbsp;{{ $error }}
    </p>
    @endforeach
  </div>
  @endif
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Bahan</h4>
            <div class="card-header-action">
              <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalImport">
                <i class="fas fa-upload"></i> Import
              </button>
              <a href="{{ url('admin/bahan/export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> Download Format Excel
              </a>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <form action="{{ url('admin/bahan') }}" method="get" id="get-filter">
                <div class="float-left mb-3 mr-3">
                  <select class="form-control selectric" name="tempat" onchange="event.preventDefault();
                  document.getElementById('get-filter').submit();">
                    <option value="">Semua Tempat</option>
                    @foreach ($tempats as $tempat)
                    <option value="{{ $tempat->id }}" {{ Request::get('tempat')==$tempat->id ? 'selected' : '' }}>{{ $tempat->nama }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="float-xs-right float-sm-right float-left mb-3">
                  <div class="input-group">
                    <input type="search" class="form-control" name="keyword" placeholder="Cari"
                      value="{{ Request::get('keyword') }}" autocomplete="off" onsubmit="event.preventDefault();
                    document.getElementById('get-filter').submit();">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Nama</th>
                    <th>Stok</th>
                    <th>Tempat Bahan</th>
                    <th class="text-center">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($bahans as $key => $bahan)
                  <tr>
                    <td class="text-center">{{ $bahans->firstItem() + $key }}</td>
                    <td>{{ $bahan->nama }}</td>
                    <td>{{ $bahan->stok }} {{ $bahan->satuan->singkatan }}</td>
                    <td>{{ $bahan->ruang->tempat->nama }}</td>
                    <td class="text-center w-25">
                      <form action="{{ url('admin/bahan/' . $bahan->id) }}" method="post" id="del-{{ $bahan->id }}">
                        @csrf
                        @method('delete')
                        <a href="{{ url('admin/bahan/' . $bahan->id) }}" class="btn btn-info">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ url('admin/bahan/' . $bahan->id . '/edit') }}" class="btn btn-warning">
                          <i class="fas fa-pen"></i>
                        </a>
                        <button type="submit" class="btn btn-danger"
                          data-confirm="Hapus Data?|Apakah anda yakin menghapus bahan <b>{{ $bahan->nama }}</b>?"
                          data-confirm-yes="modalDelete({{ $bahan->id }})">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
              <div class="pagination p-4">
                {{ $bahans->appends(Request::all())->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" tabindex="-1" role="dialog" id="modalImport">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ url('admin/bahan/import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="file">File Data Bahan</label>
            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror"
              value="{{ old('file') }}">
            @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function modalDelete(id) {
    $("#del-" + id).submit();
  }
</script>
@endsection