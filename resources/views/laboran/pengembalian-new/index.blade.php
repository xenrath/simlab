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
                        <table class="table table-striped table-bordered table-md">
                            <tr>
                                <th class="text-center" style="width: 20px">No.</th>
                                <th>Peminjam</th>
                                <th>Waktu</th>
                                <th>Praktik</th>
                                <th style="width: 180px">Opsi</th>
                            </tr>
                            @forelse($pinjams as $pinjam)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ url('laboran/hubungi/' . $pinjam->peminjam_id) }}"
                                            target="_blank">
                                            {{ $pinjam->peminjam->nama }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                                            $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                                            $now = Carbon\Carbon::now()->format('Y-m-d');
                                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
                                        @endphp
                                        @if ($pinjam->praktik_id == 3)
                                            {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                                        @else
                                            {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }} <br> {{ $tanggal_awal }}
                                        @endif
                                        @if ($now > $expire)
                                            <i class="fas fa-exclamation-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pinjam->praktik_id != null)
                                            @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 4)
                                                {{ $pinjam->praktik->nama }} <br>
                                                ({{ $pinjam->ruang->nama }})
                                            @else
                                                {{ $pinjam->praktik->nama }} <br>
                                                ({{ $pinjam->keterangan }})
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('laboran/pengembalian-new/' . $pinjam->id) }}"
                                            class="btn btn-primary">
                                            Konfirmasi
                                        </a>
                                        <button class="btn btn-danger"
                                            data-confirm="Hapus Peminjaman|Apakah anda yakin menghapus peminjaman ini?"
                                            data-confirm-yes="modalDelete({{ $pinjam->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <form action="{{ url('laboran/pengembalian-new/' . $pinjam->id) }}" method="POST"
                                            id="delete-{{ $pinjam->id }}">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                                </tr>
                            @endforelse
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
