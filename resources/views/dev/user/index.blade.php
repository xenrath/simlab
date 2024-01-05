@extends('layouts.app')

@section('title', 'Data User')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data User</h1>
            <div class="section-header-button">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah</button>
            </div>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <div class="alert-title">Error</div>
                </div>
                @foreach (session('error') as $error)
                    <p>
                        <span class="bullet"></span>&nbsp;{{ $error }}
                    </p>
                @endforeach
            </div>
        @endif
        @if (session('failures'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <div class="alert-title">Error</div>
                </div>
                @foreach (session('failures') as $fail)
                    <p>
                        <span class="bullet"></span>&nbsp;
                        Baris ke {{ $fail->row() }} : <strong>{{ $fail->values()[$fail->attribute()] }}</strong>,
                        @foreach ($fail->errors() as $error)
                            {{ $error }}
                        @endforeach
                    </p>
                @endforeach
            </div>
        @endif
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data User</h4>
                            <div class="card-header-action dropdown">
                                <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle">Menu</a>
                                <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                    <li class="dropdown-title">Pilih Menu</li>
                                    <li>
                                        <a href="{{ url('dev/user/export') }}" class="dropdown-item">Download Format
                                            Excel</a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item" data-toggle="modal"
                                            data-target="#modalImport">Import User</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('dev/user/trash') }}" class="dropdown-item">Sampah</a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item" data-toggle="modal"
                                            data-target="#modal-aktivasi">Aktivasi
                                            User</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('dev/user/refresh-user') }}" class="dropdown-item">Refresh User</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                <form action="{{ url('dev/user') }}" method="GET" id="get-filter">
                                    <div class="float-left mb-3 mr-3" style="width: 160px">
                                        <select class="form-control selectric" name="filter"
                                            onchange="event.preventDefault(); document.getElementById('get-filter').submit();">
                                            <option value="role"
                                                {{ Request::get('filter') == 'role' ? 'selected' : null }}>Role User
                                            </option>
                                            <option value="updated_at"
                                                {{ Request::get('filter') == 'updated_at' ? 'selected' : null }}>
                                                Terakhir
                                                Diubah</option>
                                        </select>
                                    </div>
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
                                <table class="table table-bordered table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Nama</th>
                                            <th>Role</th>
                                            <th>Tanggal Diubah</th>
                                            <th class="text-center" style="width: 200px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $key => $user)
                                            <tr>
                                                <td class="text-center">{{ $users->firstItem() + $key }}</td>
                                                <td class="text-wrap">{{ $user->nama }}</td>
                                                <td>{{ ucfirst($user->role) }}</td>
                                                <td>
                                                    @if ($user->updated_at != null)
                                                        {{ date('d M Y', strtotime($user->updated_at)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ url('dev/user/' . $user->id) }}" method="POST"
                                                        id="del-{{ $user->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{ url('dev/user/' . $user->id) }}" class="btn btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ url('dev/user/' . $user->id . '/edit') }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Apakah anda yakin menghapus user <b>{{ $user->nama }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $user->id }})">
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
                            <div class="pagination">
                                {{ $users->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-tambah">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/user/create') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label for="role">Role</label>
                            <select class="custom-select custom-select-sm" name="role">
                                <option value="">- Pilih -</option>
                                <option value="admin">Admin</option>
                                <option value="kalab">Kalab</option>
                                <option value="laboran">Laboran</option>
                                <option value="peminjam">Peminjam</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalImport">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/user/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">File *</label>
                            <input type="file" class="form-control" id="file" name="file"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-aktivasi">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aktivasi User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/user/aktivasi') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Prodi</label>
                            <select class="form-control selectric" name="subprodi_id" id="subprodi_id">
                                <option value="">- Pilih -</option>
                                @foreach ($subprodis as $subprodi)
                                    <option value="{{ $subprodi->id }}">{{ $subprodi->jenjang }} {{ $subprodi->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function modalDelete(id) {
            $("#del-" + id).submit();
        }
    </script>
@endsection
