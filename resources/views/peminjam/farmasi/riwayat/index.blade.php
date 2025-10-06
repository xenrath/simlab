@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Riwayat Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="row">
                @forelse ($pinjams as $pinjam)
                    <div class="col-md-4">
                        <div class="card rounded-0 mb-3">
                            <div class="card-body">
                                <ul class="p-0" style="list-style: none">
                                    <li>
                                        <h6 class="d-flex justify-content-between align-items-center mb-2">
                                            {{ $pinjam->praktik->nama }}
                                            @if ($pinjam->kategori == 'normal')
                                                <span class="badge badge-success rounded-0">Mandiri</span>
                                            @else
                                                <span class="badge badge-warning rounded-0">Estafet</span>
                                            @endif
                                        </h6>
                                    </li>
                                    <li>
                                        @if ($pinjam->praktik_id == '1')
                                            {{ $pinjam->ruang->nama }}
                                        @endif
                                    </li>
                                    <li>
                                        <span class="text-muted">
                                            @if ($pinjam->kategori == 'normal')
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                                -
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                            @elseif ($pinjam->kategori == 'estafet')
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }},
                                                {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                                <div class="btn-group">
                                    <button class="btn btn-info rounded-0 btn-sm dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opsi
                                    </button>
                                    <div class="dropdown-menu rounded-0">
                                        <a class="dropdown-item"
                                            href="{{ url('peminjam/farmasi/riwayat/' . $pinjam->id) }}">Lihat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="card rounded-0 mb-3">
                            <div class="card-body p-5 text-center">
                                <span class="text-muted">- Data tidak ditemukan -</span>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            @if ($pinjams->total() > 6)
                <div class="justify-content-center bg-white rounded-0 d-flex mt-3 pt-3">
                    {{ $pinjams->appends(Request::all())->links('pagination::simple-bootstrap-4') }}
                </div>
            @endif
        </div>
    </section>
@endsection
