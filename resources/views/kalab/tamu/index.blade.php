@extends('layouts.app')

@section('title', 'Data Tamu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Tamu</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Tamu</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('kalab/tamu') }}" method="GET" id="form-search">
                        <div class="row justify-content-end">
                            <div class="col-md-3 mb-2">
                                <div class="input-group">
                                    <input type="search" class="form-control rounded-0" name="keyword"
                                        placeholder="cari nama / institusi" value="{{ Request::get('keyword') }}"
                                        autocomplete="off">
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
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>Nama</th>
                                    <th>Institusi</th>
                                    <th style="width: 20px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tamus as $key => $tamu)
                                    <tr>
                                        <td class="text-center">{{ $tamus->firstItem() + $key }}</td>
                                        <td>{{ $tamu->nama }}</td>
                                        <td>{{ $tamu->institusi }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info rounded-0" data-toggle="modal"
                                                data-target="#modal-detail-{{ $tamu->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
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
    @foreach ($tamus as $tamu)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-detail-{{ $tamu->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Tamu</h5>
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
                                    <a href="{{ url('kalab/hubungi_tamu/' . $tamu->id) }}" target="_blank">
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
    @endforeach
@endsection

@section('script')
    <script>
        $('#keyword').on('search', function() {
            search_get();
        });

        function search_get(is_btn) {
            if (is_btn) {
                $('#btn-search').prop('disabled', true);
                $('#btn-search-text').hide();
                $('#btn-search-load').show();
            }
            $('#form-search').submit();
        }
    </script>
@endsection
