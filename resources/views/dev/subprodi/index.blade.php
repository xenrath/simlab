@extends('layouts.app')

@section('title', 'Data Sub Prodi')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Sub Prodi</h1>
    <div class="section-header-button">
      <a href="{{ url('dev/subprodi/create') }}" class="btn btn-primary">Tambah</a>
    </div>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Sub Prodi</h4>
      </div>
      <div class="card-body p-0">
        <div class="p-4">
          <form action="{{ url('dev/subprodi') }}" method="get" id="get-kategori">
            <div class="float-xs-right float-sm-right float-left mb-3">
              <div class="input-group">
                <input type="search" class="form-control" name="keyword" placeholder="Cari"
                  value="{{ Request::get('keyword') }}" autocomplete="off" onsubmit="event.preventDefault();
                document.getElementById('get-keyword').submit();">
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
                <th>Jenjang</th>
                <th>Nama Program Studi</th>
                <th class="text-center">Opsi</th>
              </tr>
            </thead>
            <tbody id="data-barang">
              @forelse ($subprodis as $key => $subprodi)
              <tr>
                <td class="text-center">{{ $subprodis->firstItem() + $key }}</td>
                <td>{{ $subprodi->jenjang }}</td>
                <td>{{ $subprodi->nama }}</td>
                <td class="text-center">
                  <form action="{{ url('dev/subprodi/' . $subprodi->id) }}" method="POST" id="del-{{ $subprodi->id }}">
                    @csrf
                    @method('delete')
                    <a href="{{ url('dev/subprodi/' . $subprodi->id . '/edit' ) }}" class="btn btn-warning">
                      <i class="fa fa-pen"></i>
                    </a>
                    <button type="submit" class="btn btn-danger"
                      data-confirm="Hapus Data?|Apakah anda yakin menghapus mata kuliah <b>{{ $subprodi->nama }}</b>?"
                      data-confirm-yes="modalDelete({{ $subprodi->id }})">
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
          <div class="pagination">
            {{ $subprodis->appends(Request::all())->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
<script>
  function modalDelete(id) {
    $("#del-" + id).submit();
  }
</script>
@endsection