@extends('layouts.app')

@section('title', 'Bahan Pemasukan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/perawat/bahan') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Bahan Pemasukan</h1>
            <div class="section-header-button">
                <button type="button" class="btn btn-primary rounded-0" data-toggle="modal" data-target="#modal-tambah">
                    Buat
                </button>
            </div>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Pemasukan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('laboran/perawat/bahan-pemasukan') }}" method="get" id="form-filter">
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
                                    <th>Nama Bahan</th>
                                    <th>Prodi</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th class="text-center" style="width: 60px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekap_bahans as $key => $rekap_bahan)
                                    <tr>
                                        <td class="text-center">{{ $rekap_bahans->firstItem() + $key }}</td>
                                        <td>{{ $rekap_bahan->bahan_nama }}</td>
                                        <td>{{ $rekap_bahan->prodi_nama }}</td>
                                        <td>
                                            {{ $rekap_bahan->jumlah }}
                                            {{ $rekap_bahan->satuan }}
                                        </td>
                                        <td>{{ Carbon\Carbon::parse($rekap_bahan->created_at)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger rounded-0" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $rekap_bahan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($rekap_bahans->total() > 10)
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $rekap_bahans->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-tambah">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-3 border-bottom">
                    <h5 class="modal-title">Buat Pemasukan Bahan</h5>
                </div>
                <form action="{{ url('laboran/perawat/bahan-pemasukan/create') }}" method="GET" id="form-tambah">
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="metode">Metode</label>
                            <select class="custom-select custom-select-sm rounded-0 @error('metode') is-invalid @enderror"
                                name="metode" id="metode">
                                <option value="">- Pilih -</option>
                                <option value="manual">Pilih Manual</option>
                                <option value="scan">Scan Barcode</option>
                            </select>
                            @error('metode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary rounded-0" id="btn-tambah" onclick="form_tambah()">
                            <div id="btn-tambah-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-tambah-text">Selanjutnya</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($rekap_bahans as $rekap_bahan)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus-{{ $rekap_bahan->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-3 border-bottom">
                        <h5 class="modal-title">Hapus Pemasukan Bahan</h5>
                    </div>
                    <div class="modal-body">
                        Yakin hapus pemasukan bahan
                        <strong>
                            {{ $rekap_bahan->bahan_nama }}
                            ({{ $rekap_bahan->jumlah }} {{ $rekap_bahan->satuan }})
                        </strong>?
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('laboran/perawat/bahan-pemasukan/' . $rekap_bahan->id) }}" method="POST"
                            id="form-hapus-{{ $rekap_bahan->id }}">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-danger rounded-0"
                                id="btn-hapus-{{ $rekap_bahan->id }}" onclick="form_hapus({{ $rekap_bahan->id }})">
                                <div id="btn-hapus-load-{{ $rekap_bahan->id }}" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-hapus-text-{{ $rekap_bahan->id }}">Hapus</span>
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
        function form_tambah() {
            $('#btn-tambah').prop('disabled', true);
            $('#btn-tambah-text').hide();
            $('#btn-tambah-load').show();
            $('#form-tambah').submit();
        }

        function form_hapus(id) {
            $('#btn-hapus-' + id).prop('disabled', true);
            $('#btn-hapus-text-' + id).hide();
            $('#btn-hapus-load-' + id).show();
            $('#form-hapus-' + id).submit();
        }
    </script>
@endsection
