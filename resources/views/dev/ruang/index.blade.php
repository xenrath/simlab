@extends('layouts.app')

@section('title', 'Data Ruang')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Ruang / Lab</h1>
    <div class="section-header-button">
      <a href="{{ url('dev/ruang/create') }}" class="btn btn-primary">Tambah</a>
    </div>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Ruang / Lab</h4>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <form action="{{ url('dev/ruang') }}" method="GET" id="get-filter">
                <div class="float-left mb-3 mr-3" style="width: 140px">
                  <select class="form-control selectric" name="prodi_id" onchange="event.preventDefault();
                  document.getElementById('get-filter').submit();">
                    <option value="">- Pilih -</option>
                    @foreach ($prodis as $prodi)
                    <option value="{{ $prodi->id }}" {{ Request::get('prodi_id')==$prodi->id ? 'selected' : '' }}>{{ ucfirst($prodi->nama) }}
                    </option>
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
                    <th>Kode</th>
                    <th>Ruang / Lab</th>
                    <th>Laboran</th>
                    <th class="text-center">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($ruangs as $ruang)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $ruang->kode }}</td>
                    <td>{{ $ruang->nama }}</td>
                    <td>{{ $ruang->laboran->nama }}</td>
                    <td class="text-center w-25">
                      <form action="{{ url('dev/ruang/' . $ruang->id) }}" method="POST" id="del-{{ $ruang->id }}">
                        @csrf
                        @method('delete')
                        <a href="{{ url('dev/ruang/' . $ruang->id . '/edit') }}" class="btn btn-warning">
                          <i class="fas fa-pen"></i>
                        </a>
                        <button type="submit" class="btn btn-danger"
                          data-confirm="Hapus Data?|Apakah anda yakin menghapus ruangan <b>{{ $ruang->nama }}</b>?"
                          data-confirm-yes="modalDelete({{ $ruang->id }})">
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
              {{ $ruangs->appends(Request::all())->links('pagination::bootstrap-4') }}
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