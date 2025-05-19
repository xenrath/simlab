@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Laporan Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-2">
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
                        <table class="table table-striped table-bordered table-md">
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Peminjam</th>
                                <th>Praktik</th>
                                <th>Waktu</th>
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
                                        Praktik {{ $pinjam->kategori == 'normal' ? 'Mandiri' : 'Estafet' }} <br>
                                        <small>({{ $pinjam->ruang->nama }})</small>
                                    </td>
                                    <td>
                                        @if ($pinjam->kategori == 'normal')
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d M Y') }} -
                                            {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                        @elseif ($pinjam->kategori == 'estafet')
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d M Y') }}
                                            <br>
                                            {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                                </tr>
                            @endforelse
                        </table>
                        @if ($pinjams->total() > 10)
                            <div class="pagination px-4 py-2 d-flex justify-content-md-end">
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
                <form action="{{ url('laboran/laporan/print-farmasi') }}" method="post" id="form-print">
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
