@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Laporan Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                    <div class="card-header-action">
                        <button type="button" class="btn btn-outline-primary rounded-0" data-toggle="modal"
                            data-target="#modal-print">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md mb-0">
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Peminjam</th>
                                <th>Waktu</th>
                                <th>Praktik</th>
                                <th style="width: 40px">Opsi</th>
                            </tr>
                            @forelse($pinjams as $key => $pinjam)
                                <tr>
                                    <td class="text-center">{{ $pinjams->firstItem() + $key }}</td>
                                    <td>
                                        <a href="{{ url('laboran/hubungi/' . $pinjam->peminjam_id) }}" target="_blank">
                                            {{ $pinjam->peminjam->nama }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($pinjam->praktik_id == '3')
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                            -
                                            <br>
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                        @else
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                            <br>
                                            {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pinjam->praktik_id != null)
                                            {{ $pinjam->praktik->nama }} <br>
                                            @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 4)
                                                <small>({{ $pinjam->ruang->nama }})</small>
                                            @else
                                                <small>({{ $pinjam->keterangan }})</small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('laboran/k3/laporan/' . $pinjam->id) }}"
                                            class="btn btn-info rounded-0">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted" colspan="5">- Data tidak ditemukan -</td>
                                </tr>
                            @endforelse
                        </table>
                        @if ($pinjams->total() > 10)
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $pinjams->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-print" role="dialog" aria-labelledby="modal-print">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Print Laporan</h5>
                </div>
                <form action="{{ url('laboran/k3/laporan/print') }}" method="post" id="form-print">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="tanggal_awal">Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal"
                                class="form-control rounded-0  @error('tanggal_awal') is-invalid @enderror"
                                value="{{ old('tanggal_awal') }}">
                            @error('tanggal_awal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                class="form-control rounded-0  @error('tanggal_akhir') is-invalid @enderror"
                                value="{{ old('tanggal_akhir') }}">
                            @error('tanggal_akhir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary rounded-0" id="btn-print" onclick="form_print()">
                            <div id="btn-print-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-print-text">Print</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function form_print() {
            $('#btn-print').prop('disabled', true);
            $('#btn-print-text').hide();
            $('#btn-print-load').show();
            $('#form-print').submit();
        }
    </script>
@endsection
