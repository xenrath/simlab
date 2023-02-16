@extends('layouts.app')

@section('title', 'Data Laboran')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Laboran</h1>
    <div class="section-header-button">
      <a href="{{ url('admin/laboran/create') }}" class="btn btn-primary">Tambah Laboran</a>
    </div>
  </div>
  {{-- @if ($errors->any())
  <div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      @foreach ($errors->all() as $error)
      {{ $error }}
      @endforeach
    </div>
  </div>
  @endif --}}
  @if ($errors->any())
  <div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <div class="alert-title">Error</div>
    </div>
    @foreach ($errors->all() as $error)
    <p>{{ $error }}</p>
    @endforeach
  </div>
  @endif
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
      <div class="alert-title">GAGAL !</div>
    </div>
    <p>{{ session('error') }}</p>
  </div>
  @endif
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Laboran</h4>
            <div class="card-header-action">
              <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalImport">
                <i class="fas fa-upload"></i> Import
              </button>
              <a href="{{ url('admin/laboran/export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Download Format Excel
              </a>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <form action="{{ url('admin/laboran') }}" method="GET" id="get-filter">
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
                    <th class="text-center">No</th>
                    <th>Nama Lengkap</th>
                    <th>No. Telepon</th>
                    <th>Ruang Lab.</th>
                    <th class="text-center" width="240">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($users as $key => $user)
                  <tr>
                    <td class="text-center">{{ $users->firstItem() + $key }}</td>
                    <td class="text-wrap">{{ $user->nama }}</td>
                    <td class="text-wrap">
                      @if ($user->telp)
                      +62{{ $user->telp }}
                      @else
                      -
                      @endif
                    </td>
                    <td class="text-wrap">
                      @if (count($user->ruangs) > 0)
                      @foreach ($user->ruangs as $ruang)
                      - {{ $ruang->nama }} <br>
                      @endforeach
                      @else
                      -
                      @endif
                    </td>
                    <td class="text-center">
                      <form action="{{ url('admin/laboran/' . $user->id) }}" method="POST" id="del-{{ $user->id }}">
                        @csrf
                        @method('delete')
                        <a href="{{ url('admin/laboran/' . $user->id) }}" class="btn btn-info">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ url('admin/laboran/' . $user->id . '/edit') }}" class="btn btn-warning">
                          <i class="fas fa-pen"></i>
                        </a>
                        <button type="submit" class="btn btn-danger"
                          data-confirm="Hapus Data?|Apakah anda yakin menghapus laboran <b>{{ $user->nama }}</b>?"
                          data-confirm-yes="modalDelete({{ $user->id }})">
                          <i class="fas fa-trash" aria-hidden="true"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="pagination float-right">
              {{ $users->appends(Request::all())->links('pagination::bootstrap-4') }}
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
        <h5 class="modal-title">Import Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ url('admin/laboran/import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="file">File</label>
            <input type="file" class="form-control" id="file" name="file"
              accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
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