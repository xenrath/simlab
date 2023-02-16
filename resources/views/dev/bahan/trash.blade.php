@extends('layouts.app')

@section('title', 'Data Bahan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('dev/bahan') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Bahan Dihapus</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Bahan</h4>
            <div class="card-header-action">
              <a href="{{ url('dev/bahan/restore') }}" class="btn btn-info btn-sm">
                <i class="fas fa-undo"></i>&nbsp;Pulihkan Semua
              </a>
              <a href="{{ url('dev/bahan/delete') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-trash"></i> Hapus Semua
              </a>
            </div>
          </div>
          <div class="card-body p-0">
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
                      <a href="{{ url('dev/bahan/restore/' . $bahan->id) }}" class="btn btn-info">
                        <i class="fas fa-undo"></i>
                      </a>
                      <a href="{{ url('dev/bahan/delete/' . $bahan->id) }}" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                      </a>
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
      <form action="{{ url('dev/bahan/import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="file">File Data bahan
              <small>(Jika menggunakan import, gambar bahan tidak akan dimasukan)</small>
            </label>
            <input type="file" class="form-control" id="file" name="file"
              accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
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