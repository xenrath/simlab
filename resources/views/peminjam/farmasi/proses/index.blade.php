@extends('layouts.app')

@section('title', 'Dalam Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dalam Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="row">
                @foreach ($pinjams as $pinjam)
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <ul class="p-0" style="list-style: none">
                                    @php
                                        if ($pinjam->kategori == 'normal') {
                                            $kategori = 'Mandiri';
                                        } else {
                                            $kategori = 'Estafet';
                                        }
                                    @endphp
                                    <li>
                                        <strong>{{ $pinjam->praktik->nama }} ({{ $kategori }})</strong>
                                    </li>
                                    <li>
                                        @if ($pinjam->praktik_id == '1')
                                            {{ $pinjam->ruang->nama }}
                                        @endif
                                    </li>
                                    <li>
                                        <span class="text-muted">
                                            @if ($pinjam->kategori == 'normal')
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                                {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                            @elseif ($pinjam->kategori == 'estafet')
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }},
                                                {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}
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
                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opsi
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ url('peminjam/farmasi/proses/' . $pinjam->id) }}">Lihat</a>
                                        @if ($pinjam->peminjam_id == auth()->user()->id)
                                            <a class="dropdown-item"
                                                href="{{ url('peminjam/farmasi/proses/' . $pinjam->id . '/edit') }}">Edit</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
