@extends('layouts.app')

@section('title', 'Data Berita')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Master Data Berita</h1>
      <div class="section-header-button">
        <a href="{{ url('web/berita/create') }}" class="btn btn-primary">Tambah</a>
      </div>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Berita</h4>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th class="text-center">No.</th>
                      <th>Tanggal</th>
                      <th>Judul</th>
                      <th class="text-center">Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($beritas as $berita)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ date('d M Y', strtotime($berita->created_at)) }}</td>
                        <td>{{ $berita->judul }}</td>
                        <td class="text-center w-25">
                          <form action="{{ url('web/berita/' . $berita->id) }}" method="POST"
                            id="del-{{ $berita->id }}">
                            @csrf
                            @method('delete')
                            <a href="{{ url('web/berita/' . $berita->id) }}" class="btn btn-info">
                              <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ url('web/berita/' . $berita->id . '/edit') }}" class="btn btn-warning">
                              <i class="fas fa-pen"></i>
                            </a>
                            <button type="submit" class="btn btn-danger"
                              data-confirm="Hapus Data?|Apakah anda yakin menghapus berita <b>{{ $berita->judul }}</b>?"
                              data-confirm-yes="modalDelete({{ $berita->id }})">
                              <i class="fas fa-trash" aria-hidden="true"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td class="text-center" colspan="4">- Data tidak ditemukan -</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
                <div class="pagination">
                  {{ $beritas->appends(Request::all())->links('pagination::bootstrap-4') }}
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
        <form action="{{ url('web/berita/import') }}" method="POST" enctype="multipart/form-data">
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
