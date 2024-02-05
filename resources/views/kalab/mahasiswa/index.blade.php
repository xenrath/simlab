@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Mahasiswa</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Mahasiswa</h4>
                        </div>
                        <div class="card-body p-0">
                            <form action="{{ url('kalab/mahasiswa') }}" method="GET" id="get-filter">
                                <div class="row pt-3 px-4">
                                    <div class="col-md-3 mb-3">
                                        <select class="custom-select custom-select-sm" name="subprodi_id"
                                            onchange="event.preventDefault(); document.getElementById('get-filter').submit();">
                                            <option value=""
                                                {{ Request::get('subprodi_id') == '' ? 'selected' : null }}>
                                                Semua Prodi
                                            </option>
                                            @foreach ($subprodis as $subprodi)
                                                <option value="{{ $subprodi->id }}"
                                                    {{ Request::get('subprodi_id') == $subprodi->id ? 'selected' : null }}>
                                                    {{ $subprodi->jenjang }}
                                                    {{ $subprodi->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-0 col-md-6"></div>
                                    <div class="col-md-3 mb-3">
                                        <div class="input-group">
                                            <input type="search" class="form-control" name="keyword"
                                                placeholder="cari nama / nim" value="{{ Request::get('keyword') }}"
                                                autocomplete="off"
                                                onsubmit="event.preventDefault();
                document.getElementById('get-filter').submit();">
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
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Prodi</th>
                                            <th style="width: 20px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $key => $user)
                                            <tr>
                                                <td class="text-center">{{ $users->firstItem() + $key }}</td>
                                                <td class="text-wrap">
                                                    {{ $user->kode }}
                                                </td>
                                                <td class="text-wrap">{{ $user->nama }}</td>
                                                <td class="text-wrap">
                                                    @if ($user->subprodi_id)
                                                        {{ $user->subprodi->jenjang }} {{ $user->subprodi->nama }}
                                                    @else
                                                        {{ $user->alamat }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('kalab/mahasiswa/' . $user->id) }}"
                                                        class="btn btn-info">
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
                        @if ($users->total() > 10)
                            <div class="card-footer">
                                <div class="pagination float-right">
                                    {{ $users->appends(Request::all())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
