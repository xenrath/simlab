@extends('layouts.app')

@section('title', 'Peminjaman Tagihan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman Tagihan</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
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
                                        Praktik {{ $pinjam->kategori == 'normal' ? 'Mandiri' : 'Estafet' }} <br>
                                        ({{ $pinjam->ruang->nama }})
                                    </td>
                                    <td>
                                        @if ($pinjam->kategori == 'normal')
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                            {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                        @elseif ($pinjam->kategori == 'estafet')
                                            {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                        @endif
                                        @php
                                            $now = Carbon\Carbon::now()->format('Y-m-d');
                                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_awal));
                                        @endphp
                                        @if ($now > $expire)
                                            <i class="fas fa-exclamation-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('laboran/tagihan/' . $pinjam->id) }}" class="btn btn-primary">
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
