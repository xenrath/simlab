@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Laporan Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                    <div class="card-header-action">
                        <a href="{{ url('laboran/laporan/print') }}" class="btn btn-outline-primary">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
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
                                    @php
                                        $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                                        $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                                    @endphp
                                    <td>
                                        @if ($pinjam->praktik_id == '3')
                                            {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                                        @else
                                            {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }} <br> {{ $tanggal_awal }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pinjam->praktik_id != null)
                                            @if ($pinjam->praktik_id == '1')
                                                {{ $pinjam->praktik->nama }} <br>
                                                ({{ $pinjam->ruang->nama }})
                                            @else
                                                {{ $pinjam->praktik->nama }} <br>
                                                ({{ $pinjam->keterangan }})
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('laboran/laporan/' . $pinjam->id) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
@endsection
