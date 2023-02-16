@extends('layouts.app')

@section('title', 'Data Ruangan')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Ruangan Laboran</h1>
    <div class="section-header-button">
      <a href="{{ url('admin/ruang/create') }}" class="btn btn-primary">Tambah</a>
    </div>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Ruangan</h4>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <form action="{{ url('admin/ruang') }}" method="GET" id="get-filter">
                <div class="float-left mb-3 mr-3">
                  <select class="form-control selectric" name="prodi" onchange="event.preventDefault();
                  document.getElementById('get-filter').submit();">
                    <option value="">- Pilih Prodi -</option>
                    <option value="keperawatan" {{ Request::get('prodi')=='keperawatan' ? 'selected' : '' }}>Keperawatan</option>
                    <option value="kebidanan" {{ Request::get('prodi')=='kebidanan' ? 'selected' : '' }}>Kebidanan</option>
                    <option value="k3" {{ Request::get('prodi')=='k3' ? 'selected' : '' }}>K3</option>
                    <option value="farmasi" {{ Request::get('prodi')=='farmasi' ? 'selected' : '' }}>Farmasi</option>
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
                    <th>Nama Ruang / Lab</th>
                    <th>Prodi</th>
                    <th>Laboran</th>
                    <th class="text-center">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($ruangs as $ruang)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-wrap" width="240">{{ $ruang->nama }}</td>
                    <td>{{ ucfirst($ruang->prodi) }}</td>
                    <td>{{ $ruang->laboran->nama }}</td>
                    <td class="text-center w-25">
                      <form action="{{ url('admin/ruang/' . $ruang->id) }}" method="POST" id="del-{{ $ruang->id }}">
                        @csrf
                        @method('delete')
                        <a href="{{ url('admin/ruang/' . $ruang->id . '/edit') }}" class="btn btn-warning">
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