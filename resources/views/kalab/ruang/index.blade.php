@extends('layouts.app')

@section('title', 'Data Ruang')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Ruang</h1>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Ruang</h4>
            </div>
            <div class="card-body p-0">
              <div class="p-4">
                <form action="{{ url('ruang') }}" method="GET">
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
                      <th>Ruang / Lab</th>
                      <th>Laboran</th>
                      <th class="text-center" width="240">Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($ruangs as $key => $ruang)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $ruang->nama }}</td>
                        <td>{{ $ruang->laboran->nama }}</td>
                        <td class="text-center">
                          <a href="{{ url('kalab/ruang/' . $ruang->id) }}" class="btn btn-info">
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
                {{ $ruangs->appends(Request::all())->links('pagination::bootstrap-4') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
