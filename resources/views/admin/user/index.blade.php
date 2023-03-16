@extends('layouts.app')

@section('title', 'Data User')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Data User</h1>
      <div class="section-header-button">
        <a href="{{ url('admin/user/create') }}" class="btn btn-primary">Tambah</a>
      </div>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data User</h4>
              <div class="card-header-action">
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalImport">
                  <i class="fas fa-upload"></i> Import
                </button>
                <a href="{{ url('admin/exportpeminjam') }}" class="btn btn-success">
                  <i class="fas fa-download"></i> Download Format Excel
                </a>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="p-4">
                <form action="{{ url('admin/user') }}" method="GET" id="get-filter">
                  <div class="float-left mb-3 mr-3">
                    <select class="form-control selectric" name="role"
                      onchange="event.preventDefault(); document.getElementById('get-filter').submit();">
                      <option value="" {{ Request::get('role') == '' ? 'selected' : null }}>Semua</option>
                      <option value="laboran" {{ Request::get('role') == 'laboran' ? 'selected' : null }}>Laboran</option>
                      <option value="peminjam" {{ Request::get('role') == 'peminjam' ? 'selected' : null }}>Peminjam
                      </option>
                    </select>
                  </div>
                  <div class="float-xs-right float-sm-right float-left mb-3">
                    <div class="input-group">
                      <input type="search" class="form-control" name="keyword" placeholder="Cari"
                        value="{{ Request::get('keyword') }}" autocomplete="off"
                        onsubmit="event.preventDefault();
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
                      <th>Nama</th>
                      <th>Role</th>
                      <th class="text-center">Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td class="text-center w-25">
                          <form action="{{ url('admin/user/' . $user->id) }}" method="POST"
                            id="del-{{ $user->id }}">
                            @csrf
                            @method('delete')
                            <a href="{{ url('admin/user/' . $user->id) }}" class="btn btn-info">
                              <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ url('admin/user/' . $user->id . '/edit') }}" class="btn btn-warning">
                              <i class="fas fa-pen"></i>
                            </a>
                            <button type="submit" class="btn btn-danger"
                              data-confirm="Hapus Data?|Apakah anda yakin menghapus user <b>{{ $user->nama }}</b>?"
                              data-confirm-yes="modalDelete({{ $user->id }})">
                              <i class="fas fa-trash" aria-hidden="true"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="pagination">
                  {{ $users->appends(Request::all())->links('pagination::bootstrap-4') }}
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
          <h5 class="modal-title">Import Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ url('admin/user/import') }}" method="POST" enctype="multipart/form-data">
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
