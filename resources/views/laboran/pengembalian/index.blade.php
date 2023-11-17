@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pengembalian</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Pengembalian</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Peminjam</th>
                                <th style="width: 240px">Praktik</th>
                                <th style="width: 240px">Waktu</th>
                                <th style="width: 120px">Opsi</th>
                            </tr>
                            @forelse($pinjams as $pinjam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ url('laboran/pengembalian/hubungi/' . $pinjam->id) }}" target="_blank">
                                            {{ $pinjam->peminjam_nama }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $pinjam->praktik_nama }}
                                        ({{ $pinjam->kategori == 'normal' ? 'Mandiri' : 'Estafet' }})
                                    </td>
                                    <td>
                                        @if ($pinjam->kategori == 'normal')
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                            {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                        @elseif ($pinjam->kategori == 'estafet')
                                            {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}, {{ $pinjam->jam_awal }} -
                                            {{ $pinjam->jam_akhir }}
                                        @endif
                                        @php
                                            $now = Carbon\Carbon::now()->format('Y-m-d');
                                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
                                        @endphp
                                        @if ($now > $expire)
                                            <i class="fas fa-exclamation-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('laboran/pengembalian/' . $pinjam->id . '/konfirmasi') }}"
                                            class="btn btn-primary">
                                            Konfirmasi
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
            @if ($pinjams->total() > 6)
                <div class="card-footer">
                    <div class="float-right">
                        {{ $pinjams->appends(Request::all())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
