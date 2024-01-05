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
                        <table class="table table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Tamu</th>
                                    <th>Keperluan</th>
                                    <th style="width: 160px">Waktu Peminjaman</th>
                                    <th class="text-center" style="width: 120px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjaman_tamus as $peminjaman_tamu)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ url('admin/peminjaman/tagihan/hubungi/' . $peminjaman_tamu->id) }}" target="_blank">
                                                {{ $peminjaman_tamu->tamu_nama }}
                                                <br>
                                                ({{ $peminjaman_tamu->tamu_institusi }})
                                            </a>
                                        </td>
                                        <td>{{ $peminjaman_tamu->keperluan }}</td>
                                        @php
                                            $tanggal_awal = date('d M Y', strtotime($peminjaman_tamu->tanggal_awal));
                                            $tanggal_akhir = date('d M Y', strtotime($peminjaman_tamu->tanggal_akhir));
                                            $now = Carbon\Carbon::now();
                                            $expire = date('Y-m-d', strtotime($peminjaman_tamu->tanggal_akhir));
                                        @endphp
                                        <td>
                                            {{ $tanggal_awal }}<br>{{ $tanggal_akhir }}
                                        </td>
                                        <td class="align-top py-3 text-center">
                                            <a href="{{ url('admin/peminjaman/tagihan/' . $peminjaman_tamu->id) }}"
                                                class="btn btn-primary">
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
