@extends('layouts.app')

@section('title', 'Dalam Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dalam Peminjaman</h1>
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
                                    <th class="text-center" style="width: 180px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjaman_tamus as $peminjaman_tamu)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ url('admin/hubungi_tamu/' . $peminjaman_tamu->id) }}"
                                                target="_blank">
                                                {{ $peminjaman_tamu->tamu->nama }}
                                                <br>
                                                ({{ $peminjaman_tamu->tamu->institusi }})
                                            </a>
                                        </td>
                                        <td>{{ $peminjaman_tamu->keperluan ?? '-' }}</td>
                                        @php
                                            $tanggal_awal = date('d M Y', strtotime($peminjaman_tamu->tanggal_awal));
                                            $tanggal_akhir = date('d M Y', strtotime($peminjaman_tamu->tanggal_akhir));
                                            $now = Carbon\Carbon::now();
                                            $expire = date('Y-m-d', strtotime($peminjaman_tamu->tanggal_akhir));
                                        @endphp
                                        <td>
                                            {{ $tanggal_awal }}
                                            <br>
                                            {{ $tanggal_akhir }}
                                            @if ($now > $expire)
                                                <i class="fas fa-exclamation-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('admin/proses/' . $peminjaman_tamu->id) }}"
                                                class="btn btn-primary">
                                                Konfirmasi
                                            </a>
                                            <button class="btn btn-danger"
                                                data-confirm="Hapus Peminjaman|Apakah anda yakin menghapus peminjaman ini?"
                                                data-confirm-yes="modalDelete({{ $peminjaman_tamu->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <form action="{{ url('admin/peminjaman/proses/' . $peminjaman_tamu->id) }}"
                                                method="POST" id="delete-{{ $peminjaman_tamu->id }}">
                                                @csrf
                                                @method('delete')
                                            </form>
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
        function modalDelete(id) {
            $("#delete-" + id).submit();
        }
    </script>
@endsection
