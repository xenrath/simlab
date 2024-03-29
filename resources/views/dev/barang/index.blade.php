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
                                            <th class="text-center" style="width: 180px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barangs as $key => $barang)
                                            <tr>
                                                <td class="text-center">{{ $barangs->firstItem() + $key }}</td>
                                                <td>{{ $barang->kode }}</td>
                                                <td>{{ $barang->nama }}</td>
                                                <td>{{ $barang->ruang->nama }}</td>
                                                <td class="text-center">
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
                            </div>
                        </div>
                        @if ($barangs->total() > 10)
                            <div class="card-footer">
                                <div class="pagination float-right">
                                    {{ $barangs->appends(Request::all())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function modalDelete(id) {
            $("#del-" + id).submit();
        }
    </script>
@endsection
