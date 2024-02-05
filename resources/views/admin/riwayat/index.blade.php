@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Riwayat Peminjaman</h1>
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
                                    <th>Waktu Peminjaman</th>
                                    <th class="text-center" style="width: 60px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjaman_tamus as $key => $peminjaman_tamu)
                                    <tr>
                                        <td class="text-center">{{ $peminjaman_tamus->firstItem() + $key }}</td>
                                        <td>
                                            <a href="{{ url('admin/hubungi_tamu/' . $peminjaman_tamu->id) }}" target="_blank">
                                                {{ $peminjaman_tamu->tamu->nama }}
                                                <br>
                                                ({{ $peminjaman_tamu->tamu->institusi }})
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
                                        <td class="text-center">
                                            <a href="{{ url('admin/riwayat/' . $peminjaman_tamu->id) }}"
                                                class="btn btn-info">
                                                <i class="fas fa-eye"></i>
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
                @if ($peminjaman_tamus->total() > 10)
                    <div class="card-footer">
                        <div class="pagination float-right">
                            {{ $peminjaman_tamus->appends(Request::all())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <script>
        function modalHapus(id) {
            $("#hapus-" + id).submit();
        }
    </script>
@endsection
