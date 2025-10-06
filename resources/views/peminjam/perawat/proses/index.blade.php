@extends('layouts.app')

@section('title', 'Dalam Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dalam Peminjaman</h1>
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
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('l, d F Y') }}
                                                <br>
                                                {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                            @elseif ($pinjam->praktik_id == 3)
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                                -
                                                <br>
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                            @endif
                                        </span>
                                        @php
                                            $now = Carbon\Carbon::now()->format('Y-m-d');
                                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
                                        @endphp
                                        @if ($now > $expire)
                                            <i class="fas fa-exclamation-circle text-danger"></i>
                                        @endif
                                    </li>
                                </ul>
                                <div class="btn-group">
                                    <button class="btn btn-info btn-sm rounded-0 dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opsi
                                    </button>
                                    <div class="dropdown-menu rounded-0">
                                        <a class="dropdown-item"
                                            href="{{ url('peminjam/perawat/proses/' . $pinjam->id) }}">Lihat</a>
                                        @if ($pinjam->peminjam_id == auth()->user()->id)
                                            @if ($pinjam->praktik_id != 4)
                                                <a class="dropdown-item"
                                                    href="{{ url('peminjam/perawat/proses/' . $pinjam->id . '/edit') }}">Edit</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <span class="text-muted">- Peminjaman proses tidak ada -</span>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
