@extends('layouts.app')

@section('title', 'Peminjaman Tagihan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman Tagihan</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-0">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md mb-0">
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Peminjam</th>
                                <th>Praktik</th>
                                <th>Waktu</th>
                                <th class="text-center" style="width: 120px">Opsi</th>
                            </tr>
                            @forelse($pinjams as $key => $pinjam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ url('laboran/hubungi/' . $pinjam->peminjam_id) }}" target="_blank">
                                            {{ $pinjam->peminjam->nama }}
                                        </a>
                                    </td>
                                    <td>
                                        Praktik {{ $pinjam->kategori == 'normal' ? 'Mandiri' : 'Estafet' }}
                                        <br>
                                        <small>({{ $pinjam->ruang->nama }})</small>
                                    </td>
                                    <td>
                                        @if ($pinjam->kategori == 'normal')
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                            -
                                            <br>
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                        @elseif ($pinjam->kategori == 'estafet')
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                            <br>
                                            {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('laboran/farmasi/tagihan/' . $pinjam->id) }}"
                                            class="btn btn-primary rounded-0">
                                            Konfirmasi
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
