@extends('layouts.app')

@section('title', 'Data Tamu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Pengguna</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Tamu</h4>
                        </div>
                        <div class="card-body p-0">
                            <form action="{{ url('kalab/tamu') }}" method="GET" id="get-filter">
                                <div class="row py-3 px-4">
                                    <div class="col-0 col-md-9"></div>
                                    <div class="col-md-3 mb-2">
                                        <div class="input-group">
                                            <input type="search" class="form-control" name="keyword"
                                                placeholder="cari nama / institusi" value="{{ Request::get('keyword') }}"
                                                autocomplete="off"
                                                onsubmit="event.preventDefault(); document.getElementById('get-filter').submit();">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Nama</th>
                                            <th>Institusi</th>
                                            <th style="width: 20px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tamus as $key => $tamu)
                                            <tr>
                                                <td class="text-center">{{ $tamus->firstItem() + $key }}</td>
                                                <td>{{ $tamu->nama }}</td>
                                                <td>{{ $tamu->institusi }}</td>
                                                <td>
                                                    <a href="{{ url('kalab/tamu/' . $tamu->id) }}" class="btn btn-info">
                                                        <i class="fas fa-eye"></i>
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
                        @if ($tamus->total() > 10)
                            <div class="card-footer">
                                <div class="pagination float-right">
                                    {{ $tamus->appends(Request::all())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
