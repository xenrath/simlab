@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman Menunggu</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered table-md">
                        <tr>
                            <th class="text-center" style="width: 20px">No.</th>
                            <th>Peminjam</th>
                            <th style="width: 220px">Praktik</th>
                            <th style="width: 220px">Waktu</th>
                            <th class="text-center" style="width: 40px">Opsi</th>
                        </tr>
                        @forelse($pinjams as $pinjam)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $pinjam->peminjam_nama }}</td>
                                <td>
                                    {{ $pinjam->praktik_nama }} ({{ $pinjam->kategori == 'normal' ? 'Mandiri' : 'Estafet' }})
                                </td>
                                <td>
                                    @if ($pinjam->kategori == 'normal')
                                        {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                        {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                    @elseif ($pinjam->kategori == 'estafet')
                                        {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}, {{ $pinjam->jam_awal }} -
                                        {{ $pinjam->jam_akhir }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('laboran/peminjaman/' . $pinjam->id) }}" class="btn btn-info">
                                        Lihat
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
    </section>
@endsection
