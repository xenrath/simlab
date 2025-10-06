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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Waktu</th>
                                    <th>Ruang (Lab)</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pinjams as $key => $pinjam)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        @php
                                            $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                                            $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                                        @endphp
                                        <td class="align-middle">
                                            {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                                        </td>
                                        <td>
                                            @if ($pinjam->praktik_id == '1')
                                                {{ $pinjam->ruang->nama }}
                                            @else
                                                {{ $pinjam->keterangan }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('peminjam/tagihan/' . $pinjam->id) }}" class="btn btn-info rounded-0">
                                                <i class="fas fa-eye"></i>
                                                <span class="d-none d-md-inline">&nbsp;Lihat</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">- Data tidak ditemukan -</td>
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
