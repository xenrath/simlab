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
                                        <strong>{{ $pinjam->praktik->nama }}</strong>
                                    </li>
                                    <li>
                                        @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 4)
                                            {{ $pinjam->ruang->nama }}
                                        @else
                                            {{ $pinjam->keterangan }}
                                        @endif
                                    </li>
                                    <li>
                                        <span class="text-muted">
                                            @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 2 || $pinjam->praktik_id == 4)
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }},
                                                {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                            @elseif ($pinjam->praktik_id == 3)
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                                -
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                                <div class="btn-group">
                                    <button class="btn btn-info btn-sm rounded-0 dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opsi
                                    </button>
                                    <div class="dropdown-menu rounded-0">
                                        <a class="dropdown-item"
                                            href="{{ url('peminjam/k3/riwayat/' . $pinjam->id) }}">Lihat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
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
