@extends('layouts.app')

@section('title', 'Data Stok Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Stok Barang</h1>
            <div class="section-header-button">
                <a href="{{ url('admin/stokbarang/create') }}" class="btn btn-primary">Tambah</a>
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
                            <h4>Stok Barang</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#modalImport">
                                    <i class="fas fa-upload"></i> Import
                                </button>
                                {{-- <a href="{{ url('admin/stokbarang/export') }}" class="btn btn-success btn-sm">
                  <i class="fas fa-download"></i> Download Format Excel
                </a> --}}
                                <a href="{{ asset('storage/uploads/file/format_import_stokbarang.xlsx') }}"
                                    class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Download Format Excel
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                <form action="{{ url('admin/stokbarang') }}" method="get" id="get-filter">
                                    <div class="float-left mb-3 mr-3">
                                        <select class="form-control selectric" name="prodi_id"
                                            onchange="event.preventDefault();
                  document.getElementById('get-filter').submit();">
                                            <option value="">Semua Prodi</option>
                                            @foreach ($prodis as $prodi)
                                                <option value="{{ $prodi->id }}"
                                                    {{ Request::get('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                                    {{ ucfirst($prodi->nama) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah Normal</th>
                                            <th>Jumlah Rusak</th>
                                            <th>Tanggal Masuk</th>
                                            <th class="text-center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stoks as $stok)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-wrap">{{ $stok->barang->nama }}</td>
                                                <td class="text-wrap">{{ $stok->normal }} {{ $stok->satuan->nama }}</td>
                                                <td class="text-wrap">{{ $stok->rusak }} {{ $stok->satuan->nama }}</td>
                                                <td class="text-wrap">{{ date('d M Y', strtotime($stok->created_at)) }}
                                                </td>
                                                <td class="text-center w-25">
                                                    <form action="{{ url('admin/stokbarang/' . $stok->id) }}"
                                                        method="POST" id="del-{{ $stok->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Yakin hapus data stok barang <b>{{ $stok->barang->nama }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $stok->id }})">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="pagination">
                                {{ $stoks->appends(Request::all())->links('pagination::bootstrap-4') }}
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
                <form action="{{ url('admin/stokbarang/import') }}" method="POST" enctype="multipart/form-data">
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
