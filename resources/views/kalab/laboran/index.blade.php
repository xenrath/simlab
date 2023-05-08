@extends('layouts.app')

@section('title', 'Data Laboran')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Laboran</h1>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Laboran</h4>
            </div>
            <div class="card-body p-0">
              <div class="p-4">
                <form action="{{ url('kalab/laboran') }}" method="GET" id="get-filter">
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
                      <th>Nama Lengkap</th>
                      <th>Ruang Lab.</th>
                      <th class="text-center" width="240">Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($users as $key => $user)
                      <tr>
                        <td class="text-center py-3 align-top">{{ $users->firstItem() + $key }}</td>
                        <td class="text-wrap py-3 align-top">{{ $user->nama }}</td>
                        <td class="text-wrap py-3 align-top p-0">
                          @if (count($user->ruangs) > 0)
                            <ul>
                              @foreach ($user->ruangs as $ruang)
                                <li>{{ $ruang->nama }}</li>
                              @endforeach
                            </ul>
                          @endif
                        </td>
                        <td class="text-center py-3 align-top">
                          <a href="{{ url('kalab/laboran/' . $user->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Detail
                          </a>
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
