@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Mahasiswa</h1>
            <div class="section-header-button">
                <a href="{{ url('admin/mahasiswa/create') }}" class="btn btn-primary rounded-0 mr-1">Tambah</a>
                <a href="{{ url('admin/mahasiswa/ubah_tingkat') }}" class="btn btn-secondary rounded-0">Ubah Semester</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Mahasiswa</h4>
                    <div class="card-header-action">
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalImport">
                            <i class="fas fa-upload"></i> Import
                        </button>
                        <a href="{{ url('admin/peminjam/export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Download Format Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/mahasiswa') }}" method="GET" id="form-search">
                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="search" class="form-control rounded-0" id="keyword" name="keyword"
                                        placeholder="Cari Nama / NIM" value="{{ Request::get('keyword') }}"
                                        autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary rounded-0"
                                            onclick="mahasiswa_search()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th style="width: 20px">NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th class="text-center" style="width: 180px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $key => $user)
                                    <tr>
                                        <td class="text-center">{{ $users->firstItem() + $key }}</td>
                                        <td>{{ $user->kode }}</td>
                                        <td>{{ $user->nama }}</td>
                                        <td>
                                            {{ $user->subprodi->jenjang }}
                                            {{ $user->subprodi->nama }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info rounded-0" data-toggle="modal"
                                                data-target="#modal-detail-{{ $user->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ url('admin/mahasiswa/' . $user->id . '/edit') }}"
                                                class="btn btn-warning rounded-0">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger rounded-0" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $user->id }}">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center text-muted" colspan="5">- Data tidak ditemukan -</td>
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
    <div class="modal fade" role="dialog" id="modalImport">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-3 border-bottom">
                    <h5 class="modal-title">Import Data</h5>
                </div>
                <form action="{{ url('admin/mahasiswa/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="file">File</label>
                            <input type="file" class="form-control rounded-0" id="file" name="file"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-0">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($users as $user)
        <div class="modal fade" role="dialog" id="modal-detail-{{ $user->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-3 border-bottom">
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
                                        +62{{ $user->telp }}
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
        <div class="modal fade" role="dialog" id="modal-hapus-{{ $user->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-3 border-bottom">
                        <h5 class="modal-title">Hapus Mahasiswa</h5>
                    </div>
                    <div class="modal-body">
                        <span>Apakah anda yakin akan menghapus mahasiswa dengan nama</span>
                        <br>
                        <strong>{{ $user->nama }}</strong>?
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('admin/mahasiswa/' . $user->id) }}" method="POST"
                            id="form-hapus-{{ $user->id }}">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-danger rounded-0" id="btn-hapus-{{ $user->id }}"
                                onclick="form_hapus({{ $user->id }})">
                                <div id="btn-hapus-load-{{ $user->id }}" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-hapus-text-{{ $user->id }}">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('script')
    <script>
        $('#keyword').on('search', function() {
            mahasiswa_search();
        });

        function mahasiswa_search() {
            $('#form-search').submit();
        }

        function form_hapus(id) {
            $('#btn-hapus-' + id).prop('disabled', true);
            $('#btn-hapus-text-' + id).hide();
            $('#btn-hapus-load-' + id).show();
            $('#form-hapus-' + id).submit();
        }
    </script>
@endsection
