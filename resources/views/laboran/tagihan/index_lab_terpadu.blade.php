@extends('layouts.app')

@section('title', 'Tagihan Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tagihan Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Tagihan</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Peminjam</th>
                                <th>Waktu</th>
                                <th>Praktik</th>
                                <th class="text-center" style="width: 120px">Opsi</th>
                            </tr>
                            @forelse($pinjams as $key => $pinjam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ url('laboran/tagihan/hubungi/' . $pinjam->id) }}" target="_blank">
                                            {{ $pinjam->user_nama }}
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
                @if ($pinjams->total() > 6)
                    <div class="card-footer">
                        <div class="float-right">
                            {{ $pinjams->appends(Request::all())->links('assets.pagination') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
