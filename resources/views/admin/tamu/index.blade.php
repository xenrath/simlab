@extends('layouts.app')

@section('title', 'Data Tamu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tamu</h1>
            <div class="section-header-button">
                <a href="{{ url('admin/tamu/create') }}" class="btn btn-primary rounded-0">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Tamu</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/tamu') }}" method="GET" id="form-search">
                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="search" class="form-control rounded-0" id="keyword" name="keyword"
                                        placeholder="Cari Nama / Institusi" value="{{ Request::get('keyword') }}"
                                        autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary rounded-0" onclick="tamu_search()">
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
                                    <th>Nama Tamu</th>
                                    <th>Institusi</th>
                                    <th class="text-center" style="width: 180px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tamus as $key => $tamu)
                                    <tr>
                                        <td class="text-center">{{ $tamus->firstItem() + $key }}</td>
                                        <td>{{ ucwords($tamu->nama) }}</td>
                                        <td>
                                            <small>{{ strtoupper($tamu->institusi) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info rounded-0" data-toggle="modal"
                                                data-target="#modal-detail-{{ $tamu->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ url('admin/tamu/' . $tamu->id . '/edit') }}"
                                                class="btn btn-warning rounded-0">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger rounded-0" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $tamu->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($tamus->total() > 10)
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $tamus->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalImport">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('admin/tamu/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">File</label>
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
    @foreach ($tamus as $tamu)
        <div class="modal fade" role="dialog" id="modal-detail-{{ $tamu->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-3 border-bottom">
                        <h5 class="modal-title">Detail Mahasiswa</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Nama Tamu</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $tamu->nama }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Institusi</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $tamu->institusi }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Telepon</strong>
                            </div>
                            <div class="col-md-6">
                                @if ($tamu->telp)
                                    <a href="{{ url('admin/hubungi_tamu/' . $tamu->id) }}" target="_blank">
                                        {{ $tamu->telp }}
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
                                {{ $tamu->alamat ?? '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br justify-content-start">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" role="dialog" id="modal-hapus-{{ $tamu->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-3 border-bottom">
                        <h5 class="modal-title">Hapus Tamu</h5>
                    </div>
                    <div class="modal-body">
                        <span>Apakah anda yakin akan menghapus tamu dengan nama</span>
                        <br>
                        <strong>{{ $tamu->nama }}</strong>?
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('admin/tamu/' . $tamu->id) }}" method="POST"
                            id="form-hapus-{{ $tamu->id }}">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-danger rounded-0" id="btn-hapus-{{ $tamu->id }}"
                                onclick="form_hapus({{ $tamu->id }})">
                                <div id="btn-hapus-load-{{ $tamu->id }}" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-hapus-text-{{ $tamu->id }}">Hapus</span>
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
            tamu_search();
        });

        function tamu_search() {
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
