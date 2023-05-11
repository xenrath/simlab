@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Data Barang</h1>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Barang</h4>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th class="text-center">No.</th>
                      <th>Nama</th>
                      <th>Tempat Barang</th>
                      <th>Jumlah Rusak</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($barangs as $key => $barang)
                      <tr>
                        <td class="text-center">{{ $barangs->firstItem() + $key }}</td>
                        <td>{{ $barang->nama }}</td>
                        <td>{{ $barang->ruang->tempat->nama }}</td>
                        <td>{{ $barang->rusak }} {{ $barang->satuan->singkatan }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
                <div class="pagination p-4">
                  {{ $barangs->appends(Request::all())->links('pagination::bootstrap-4') }}
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
        <form action="{{ url('admin/barang/import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="file">File Data Stok Barang</label>
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
  <div class="modal fade" tabindex="-1" role="dialog" id="modalImportKode">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ url('admin/barang/import-kode') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="file">File Data Update Kode</label>
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
