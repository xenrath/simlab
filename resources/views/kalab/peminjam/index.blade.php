@extends('layouts.app')

@section('title', 'Data Peminjam')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Peminjam</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Peminjam</h4>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <form action="{{ url('kalab/peminjam') }}" method="GET" id="get-filter">
                <div class="float-left mb-3 mr-3" style="width: 180px">
                  <select class="form-control selectric" name="prodi_id" onchange="event.preventDefault();
                  document.getElementById('get-filter').submit();">
                    <option value="" {{ Request::get('prodi_id')=='' ? 'selected' : null }}>Semua Prodi</option>
                    @foreach ($subprodis as $subprodi)
                    <option value="{{ $subprodi->id }}" {{ Request::get('subprodi_id')==$subprodi->id ? 'selected' : null
                      }}>{{ $subprodi->jenjang }} {{ $subprodi->nama }}</option>
                    @endforeach
                    </option>
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
                    <th class="text-center">No</th>
                    <th>Nama Lengkap</th>
                    <th>No. Telepon</th>
                    <th>Prodi</th>
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
                    <td class="text-wrap">{{ $user->subprodi->jenjang }} {{ $user->subprodi->nama }}</td>
                    <td class="text-center">
                      <a href="{{ url('kalab/peminjam/' . $user->id) }}" class="btn btn-info">
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
@endsection