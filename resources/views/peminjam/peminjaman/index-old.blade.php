@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman</h1>
            <div class="section-header-button">
                <a href="{{ url('peminjam/normal/peminjaman/create') }}" class="btn btn-primary">Buat Peminjaman</a>
            </div>
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
                                @forelse($pinjams as $pinjam)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        @php
                                            $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                                            $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                                        @endphp
                                        <td class="align-middle">
                                            {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                                        </td>
                                        <td class="align-middle text-wrap">{{ $pinjam->ruang->nama }}</td>
                                        <td class="align-middle">
                                            <form action="{{ url('peminjam/normal/peminjaman/' . $pinjam->id) }}"
                                                method="POST" id="hapus-{{ $pinjam->id }}">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ url('peminjam/normal/peminjaman/' . $pinjam->id) }}"
                                                    class="btn btn-info mr-1">
                                                    Detail
                                                </a>
                                                <button type="submit" class="btn btn-danger"
                                                    data-confirm="Batalkan Peminjaman?|Apakah anda yakin akan membatalkan peminjaman ini?"
                                                    data-confirm-yes="modalHapus({{ $pinjam->id }})">
                                                    Hapus
                                                </button>
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
        function modalHapus(id) {
            $("#hapus-" + id).submit();
        }
    </script>
@endsection
