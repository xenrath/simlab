@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Barang</h1>
            <div class="section-header-button">
                <a href="{{ url('dev/barang/create') }}" class="btn btn-primary">Tambah</a>
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
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Barang</h4>
                            <div class="card-header-action">
                                <a href="{{ url('dev/barang/trash') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-trash"></i> Sampah
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                <form action="{{ url('dev/barang') }}" method="get" id="get-kategori">
                                    <div class="float-xs-right float-sm-right float-left mb-3">
                                        <div class="input-group">
                                            <input type="search" class="form-control" name="keyword" placeholder="Cari"
                                                value="{{ Request::get('keyword') }}" autocomplete="off"
                                                onsubmit="event.preventDefault();
                    document.getElementById('get-keyword').submit();">
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
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Ruang / Lab</th>
                                            <th class="text-center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barangs as $key => $barang)
                                            <tr>
                                                <td class="text-center">{{ $barangs->firstItem() + $key }}</td>
                                                <td>{{ $barang->kode }}</td>
                                                <td>{{ $barang->nama }}</td>
                                                <td>{{ $barang->ruang->nama }}</td>
                                                <td class="text-center w-25">
                                                    <form action="{{ url('dev/barang/' . $barang->id) }}" method="post"
                                                        id="del-{{ $barang->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{ url('dev/barang/' . $barang->id) }}"
                                                            class="btn btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ url('dev/barang/' . $barang->id . '/edit') }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Yakin hapus barang <b>{{ $barang->nama }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $barang->id }})">
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
                                <div class="pagination p-4">
                                    {{ $barangs->appends(Request::all())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalImport">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/barang/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">File Data Barang
                                <small>(Jika menggunakan import, gambar barang tidak akan dimasukan)</small>
                            </label>
                            <input type="file" class="form-control" id="file" name="file"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
    <script>
        function modalDelete(id) {
            $("#del-" + id).submit();
        }
    </script>
@endsection
