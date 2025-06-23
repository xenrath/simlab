@extends('layouts.app')

@section('title', 'Data Bahan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Bahan</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Bahan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('kalab/bahan') }}" method="get" id="form-search">
                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="search" class="form-control rounded-0" id="keyword" name="keyword"
                                        placeholder="cari nama" value="{{ Request::get('keyword') }}" autocomplete="off">
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
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Nama Bahan</th>
                                    <th>Tempat</th>
                                    <th>Ruang Lab</th>
                                    <th class="text-center" style="width: 20px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="data-barang">
                                @forelse ($bahans as $key => $bahan)
                                    <tr>
                                        <td class="text-center">{{ $bahans->firstItem() + $key }}</td>
                                        <td>{{ $bahan->nama }}</td>
                                        <td>{{ $bahan->ruang->tempat->nama }}</td>
                                        <td>{{ $bahan->ruang->nama }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info rounded-0" data-toggle="modal"
                                                data-target="#modal-detail-{{ $bahan->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($bahans->total() > 10)
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $bahans->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach ($bahans as $bahan)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-detail-{{ $bahan->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Bahan</h5>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Kode</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $bahan->kode }}
                            </div>
                        </div> --}}
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
                                <strong>Ruang</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $bahan->ruang->nama }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Keterangan</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $bahan->keterangan ?? '-' }}
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
        // 
        function search_get() {
            $('#btn-search').prop('disabled', true);
            $('#btn-search-text').hide();
            $('#btn-search-load').show();
            $('#form-search').submit();
        }
    </script>
@endsection
