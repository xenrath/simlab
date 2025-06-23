@extends('layouts.app')

@section('title', 'Tagihan Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tagihan Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-md mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th style="width: 200px">Tamu</th>
                                    <th>Keperluan</th>
                                    <th style="width: 120px">Waktu</th>
                                    <th class="text-center" style="width: 40px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjaman_tamus as $peminjaman_tamu)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ url('admin/hubungi_tamu/' . $peminjaman_tamu->id) }}"
                                                target="_blank">
                                                {{ ucwords($peminjaman_tamu->tamu->nama) }}
                                            </a>
                                            <br>
                                            <small>({{ strtoupper($peminjaman_tamu->tamu->institusi) }})</small>
                                        </td>
                                        <td>{{ $peminjaman_tamu->keperluan ?? '-' }}</td>
                                        @php
                                            $tanggal_awal = date('d M Y', strtotime($peminjaman_tamu->tanggal_awal));
                                            $tanggal_akhir = date('d M Y', strtotime($peminjaman_tamu->tanggal_akhir));
                                        @endphp
                                        <td>
                                            {{ $tanggal_awal }}-
                                            <br>
                                            {{ $tanggal_akhir }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('admin/tagihan/' . $peminjaman_tamu->id) }}"
                                                class="btn btn-primary rounded-0">
                                                Konfirmasi
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
        function modalHapus(id) {
            $("#hapus-" + id).submit();
        }
    </script>
@endsection
