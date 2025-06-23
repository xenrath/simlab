@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Mahasiswa</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Mahasiswa</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('kalab/mahasiswa') }}" method="GET" id="form-search">
                        <div class="row justify-content-between">
                            <div class="col-md-3 mb-2">
                                <select class="form-control rounded-0" name="subprodi_id" onchange="search_get()">
                                    <option value="" {{ Request::get('subprodi_id') == '' ? 'selected' : null }}>
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
                            <div class="col-md-3 mb-2">
                                <div class="input-group">
                                    <input type="search" class="form-control rounded-0" id="keyword" name="keyword"
                                        placeholder="Cari Nama / NIM" value="{{ Request::get('keyword') }}"
                                        autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary rounded-0" id="btn-search"
                                            onclick="search_get(true)">
                                            <i class="fa fa-spinner fa-spin" id="btn-search-load"
                                                style="display: none;"></i>
                                            <i class="fas fa-search" id="btn-search-text"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th style="width: 20px" class="text-center">Opsi</th>
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
                                            <button type="button" class="btn btn-info rounded-0" data-toggle="modal"
                                                data-target="#modal-detail-{{ $user->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($users->total() > 10)
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $users->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach ($users as $user)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-detail-{{ $user->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Mahasiswa</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Nama Mahasiswa</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $user->nama }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>NIM</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $user->kode }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Prodi</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $user->subprodi->jenjang }}
                                {{ $user->subprodi->nama }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Tingkat</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $user->tingkat }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Telepon</strong>
                            </div>
                            <div class="col-md-6">
                                @if ($user->telp)
                                    <a href="{{ url('kalab/hubungi_user/' . $user->id) }}" target="_blank">
                                        {{ $user->telp }}
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Alamat</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $user->alamat ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br justify-content-start">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('script')
    <script>
        $('#keyword').on('search', function() {
            search_get();
        });

        function search_get(is_btn) {
            if (is_btn) {
                $('#btn-search').prop('disabled', true);
                $('#btn-search-text').hide();
                $('#btn-search-load').show();
            }
            $('#form-search').submit();
        }
    </script>
@endsection
