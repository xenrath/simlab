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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Waktu</th>
                                    <th>Praktik</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pinjams as $pinjam)
                                    <tr>
                                        <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                                        @php
                                            $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                                            $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                                            $jam_awal = $pinjam->jam_awal;
                                            $jam_akhir = $pinjam->jam_akhir;
                                            $now = Carbon\Carbon::now();
                                            $expire = date('Y-m-d H:i:s', strtotime($pinjam->tanggal_akhir . $jam_akhir));
                                        @endphp
                                        <td class="align-top py-3">
                                            @if ($pinjam->praktik_id == '3')
                                                {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                                            @else
                                                @if ($tanggal_awal == $tanggal_akhir)
                                                    {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_awal }}
                                                @else
                                                    {{ $pinjam->jam_awal }}, {{ $tanggal_awal }} <br>
                                                    {{ $pinjam->jam_akhir }}, {{ $tanggal_akhir }}
                                                @endif
                                            @endif
                                        </td>
                                        <td class="align-top py-3 text-wrap">
                                            @if ($pinjam->praktik_id != null)
                                                @if ($pinjam->praktik_id == '1')
                                                    {{ $pinjam->praktik->nama }} <br>
                                                    ({{ $pinjam->ruang->nama }})
                                                @else
                                                    {{ $pinjam->praktik->nama }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="align-top py-3">
                                            @if ($now > $expire)
                                                <span class="badge badge-danger">Kadaluarsa</span>
                                            @else
                                                <span class="badge badge-primary">Aktif</span>
                                            @endif
                                        </td>
                                        <td class="align-top py-3">
                                            <a href="{{ url('peminjam/normal/pengembalian-new/' . $pinjam->id) }}"
                                                class="btn btn-info">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function modalBatal(id) {
            $("#batal-" + id).submit();
        }
    </script>
@endsection
