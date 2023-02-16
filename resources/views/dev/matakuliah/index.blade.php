@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Mata Kuliah</h1>
    <div class="section-header-button">
      <a href="{{ url('dev/matakuliah/create') }}" class="btn btn-primary">Tambah</a>
    </div>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Mata Kuliah</h4>
      </div>
      <div class="card-body p-0">
        <div class="p-4">
          <form action="{{ url('dev/matakuliah') }}" method="get" id="get-kategori">
            <div class="float-left mb-3 mr-3">
              <select class="form-control selectric" name="prodi_id" onchange="event.preventDefault();
              document.getElementById('get-kategori').submit();">
                <option value="" {{ Request::get('prodi_id')=='' ? 'selected' : null }}>Semua</option>
                @foreach ($prodis as $prodi)
                <option value="{{ $prodi->id }}" {{ Request::get('prodi_id')==$prodi->id ? 'selected' : null }}>{{
                  $prodi->nama }}</option>
                @endforeach
              </select>
            </div>
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
                <th>Nama Mata Kuliah</th>
                <th>Prodi</th>
                <th class="text-center">Semester</th>
                <th class="text-center">Opsi</th>
              </tr>
            </thead>
            <tbody id="data-barang">
              @forelse ($matakuliahs as $key => $matakuliah)
              <tr>
                <td class="text-center">{{ $matakuliahs->firstItem() + $key }}</td>
                <td>{{ $matakuliah->nama }}</td>
                <td>{{ $matakuliah->prodi->jenjang }} {{ $matakuliah->prodi->nama }}</td>
                <td class="text-center">{{ $matakuliah->semester }}</td>
                <td class="text-center">
                  <form action="{{ url('dev/matakuliah/' . $matakuliah->id) }}" method="POST" id="del-{{ $matakuliah->id }}">
                    @csrf
                    @method('delete')
                    <a href="{{ url('dev/matakuliah/' . $matakuliah->id . '/edit' ) }}" class="btn btn-warning">
                      <i class="fa fa-pen"></i>
                    </a>
                    <button type="submit" class="btn btn-danger"
                      data-confirm="Hapus Data?|Apakah anda yakin menghapus mata kuliah <b>{{ $matakuliah->nama }}</b>?"
                      data-confirm-yes="modalDelete({{ $matakuliah->id }})">
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
            {{ $matakuliahs->appends(Request::all())->links('pagination::bootstrap-4') }}
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