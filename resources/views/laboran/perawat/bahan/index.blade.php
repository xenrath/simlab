@extends('layouts.app')

@section('title', 'Data Bahan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Bahan</h1>
            <div class="section-header-button">
                <a href="{{ url('laboran/perawat/bahan/create') }}" class="btn btn-primary rounded-0">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Bahan</h4>
                    <div class="card-header-action dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-dark dropdown-toggle">Opsi</a>
                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right rounded-0">
                            <li>
                                <a href="{{ url('laboran/perawat/bahan-pemasukan') }}" class="dropdown-item">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Pemasukan
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('laboran/perawat/bahan-pengeluaran') }}" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Pengeluaran
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('laboran/perawat/bahan') }}" method="get" id="form-filter">
                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="search" class="form-control rounded-0" id="keyword" name="keyword"
                                        placeholder="Cari Nama Bahan" value="{{ Request::get('keyword') }}"
                                        autocomplete="off" onsubmit="bahan_search()">
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
                        <table class="table table-striped table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th class="text-center" style="width: 180px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bahans as $key => $bahan)
                                    <tr>
                                        <td class="text-center">{{ $bahans->firstItem() + $key }}</td>
                                        <td>{{ $bahan->kode }}</td>
                                        <td>{{ $bahan->nama }}</td>
                                        <td>{{ $bahan->prodi?->nama ?? '-' }}</td>
                                        <td class="text-center">
                                            <button type="button" data-toggle="modal"
                                                data-target="#modal-cetak-{{ $bahan->id }}"
                                                class="btn btn-info rounded-0">
                                                <i class="fas fa-barcode"></i>
                                            </button>
                                            <a href="{{ url('laboran/perawat/bahan/' . $bahan->id . '/edit') }}"
                                                class="btn btn-warning rounded-0">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger rounded-0" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $bahan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($bahans->total() > 10)
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $bahans->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach ($bahans as $bahan)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-cetak-{{ $bahan->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-3 border-bottom">
                        <h5 class="modal-title">Cetak Barcode</h5>
                    </div>
                    <form action="{{ url('laboran/perawat/bahan/cetak/' . $bahan->id) }}" method="POST"
                        id="form-barcode-{{ $bahan->id }}">
                        @csrf
                        <div class="modal-body pb-2">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <strong>Nama Bahan</strong>
                                </div>
                                <div class="col-md-6">
                                    {{ $bahan->nama }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <strong>Prodi</strong>
                                </div>
                                <div class="col-md-6">
                                    {{ $bahan->prodi?->nama ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-body border-top">
                            <div class="form-group mb-2">
                                <label for="jumlah">Jumlah Cetak</label>
                                <input type="number"
                                    class="form-control rounded-0 @if (session('id') == $bahan->id) @error('jumlah') is-invalid @enderror @endif"
                                    id="jumlah" name="jumlah"
                                    value="{{ session('id') == $bahan->id ? old('jumlah') : '10' }}">
                                @if (session('id') == $bahan->id)
                                    @error('jumlah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke justify-content-between">
                            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-dark rounded-0" id="btn-barcode-{{ $bahan->id }}"
                                onclick="form_barcode({{ $bahan->id }})">
                                <div id="btn-barcode-load-{{ $bahan->id }}" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-barcode-text-{{ $bahan->id }}">Cetak</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus-{{ $bahan->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-3 border-bottom">
                        <h5 class="modal-title">Hapus Bahan</h5>
                    </div>
                    <div class="modal-body">
                        Yakin hapus bahan
                        <strong>
                            {{ $bahan->nama }}
                        </strong>?
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('laboran/perawat/bahan/' . $bahan->id) }}" method="POST"
                            id="form-hapus-{{ $bahan->id }}">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-danger rounded-0" id="btn-hapus-{{ $bahan->id }}"
                                onclick="form_hapus({{ $bahan->id }})">
                                <div id="btn-hapus-load-{{ $bahan->id }}" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-hapus-text-{{ $bahan->id }}">Hapus</span>
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
            bahan_search();
        });

        function bahan_search() {
            $('#form-filter').submit();
        }
    </script>
    <script>
        function form_barcode(id) {
            $('#btn-barcode-' + id).prop('disabled', true);
            $('#btn-barcode-text-' + id).hide();
            $('#btn-barcode-load-' + id).show();
            $('#form-barcode-' + id).submit();
        }

        function form_import() {
            $('#btn-import').prop('disabled', true);
            $('#btn-import-text').hide();
            $('#btn-import-load').show();
            $('#form-import').submit();
        }

        function form_hapus(id) {
            $('#btn-hapus-' + id).prop('disabled', true);
            $('#btn-hapus-text-' + id).hide();
            $('#btn-hapus-load-' + id).show();
            $('#form-hapus-' + id).submit();
        }
    </script>
@endsection
