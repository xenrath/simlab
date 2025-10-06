@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Barang</h1>
            <div class="section-header-button">
                <a href="{{ url('admin/barang/create') }}" class="btn btn-primary rounded-0">Tambah</a>
            </div>
        </div>
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
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">Gagal !</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="px-3 mb-0">
                        @foreach (session('error') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Barang</h4>
                    <div class="card-header-action">
                        {{-- <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#modal-import-kode">
                            <i class="fas fa-upload"></i> Import Update Kode
                        </button>
                        <a href="{{ asset('storage/uploads/file/format_import_updatekode.xlsx') }}"
                            class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download Format Update Kode
                        </a> --}}
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#modal-import">
                            <i class="fas fa-upload"></i> Import
                        </button>
                        <a href="{{ url('admin/barang/export') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download Format Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/barang') }}" method="get" id="form-filter">
                        <div class="row justify-content-between">
                            <div class="col-md-3">
                                <select class="custom-select custom-select-sm rounded-0" name="prodi_id"
                                    onchange="barang_search()">
                                    <option value="">Semua</option>
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->id }}"
                                            {{ Request::get('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                            {{ ucfirst($prodi->singkatan) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="search" class="form-control rounded-0" id="keyword" name="keyword"
                                        placeholder="Cari Nama Barang" value="{{ Request::get('keyword') }}"
                                        autocomplete="off" onsubmit="barang_search()">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary rounded-0" type="submit">
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
                        <table class="table table-bordered table-striped table-md mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Nama Barang</th>
                                    <th>Prodi</th>
                                    <th class="text-center" style="width: 120px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $key => $barang)
                                    <tr>
                                        <td class="text-center">{{ $barangs->firstItem() + $key }}</td>
                                        <td>{{ $barang->nama }}</td>
                                        <td>{{ ucfirst($barang->ruang->prodi->singkatan) }}</td>
                                        <td class="text-center">
                                            <form action="{{ url('admin/barang/' . $barang->id) }}" method="post"
                                                id="del-{{ $barang->id }}">
                                                @csrf
                                                @method('delete')
                                                {{-- <a href="{{ url('admin/barang/' . $barang->id) }}"
                                                    class="btn btn-info rounded-0">
                                                    <i class="fas fa-eye"></i>
                                                </a> --}}
                                                <a href="{{ url('admin/barang/' . $barang->id . '/edit') }}"
                                                    class="btn btn-warning rounded-0">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger rounded-0" data-toggle="modal"
                                                    data-target="#modal-hapus-{{ $barang->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($barangs->total() > 10)
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $barangs->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach ($barangs as $barang)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus-{{ $barang->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-3 border-bottom">
                        <h5 class="modal-title">Hapus Barang</h5>
                    </div>
                    <div class="modal-body">
                        Yakin hapus barang
                        <strong>
                            {{ $barang->nama }}
                            ({{ ucfirst($barang->ruang->prodi->singkatan) }})
                        </strong>?
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('admin/barang/' . $barang->id) }}" method="POST"
                            id="form-hapus-{{ $barang->id }}">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-danger rounded-0" id="btn-hapus-{{ $barang->id }}"
                                onclick="form_hapus({{ $barang->id }})">
                                <div id="btn-hapus-load-{{ $barang->id }}" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-hapus-text-{{ $barang->id }}">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-import">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-3 border-bottom">
                    <h5 class="modal-title">Tambah Data</h5>
                </div>
                <form action="{{ url('admin/barang/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="file">File Data Stok Barang</label>
                            <input type="file" class="form-control rounded-0" id="file" name="file"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-0">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-import-kode">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('admin/barang/import-kode') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">File Data Update Kode</label>
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
@endsection

@section('script')
    <script>
        $('#keyword').on('search', function() {
            barang_search();
        });

        function barang_search() {
            $('#form-filter').submit();
        }
    </script>
    <script>
        function form_hapus(id) {
            $('#btn-hapus-' + id).prop('disabled', true);
            $('#btn-hapus-text-' + id).hide();
            $('#btn-hapus-load-' + id).show();
            $('#form-hapus-' + id).submit();
        }
    </script>
@endsection
