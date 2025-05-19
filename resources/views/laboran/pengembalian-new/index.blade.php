@extends('layouts.app')

@section('title', 'Dalam Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dalam Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                </div>
                @if (auth()->user()->prodi_id == '7')
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-md mb-0">
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Peminjam</th>
                                    <th>Waktu</th>
                                    <th>Kategori</th>
                                    <th style="width: 180px">Opsi</th>
                                </tr>
                                @forelse($pinjams as $pinjam)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ url('laboran/hubungi/' . $pinjam->peminjam_id) }}" target="_blank">
                                                {{ $pinjam->peminjam->nama }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($pinjam->praktik_id == '3')
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                                -
                                                <br>
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                            @else
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                                <br>
                                                {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                            @endif
                                            @php
                                                $now = Carbon\Carbon::now()->format('Y-m-d');
                                                $expire = date('Y-m-d', strtotime($pinjam->tanggal_awal));
                                            @endphp
                                            @if ($now > $expire)
                                                <i class="fas fa-exclamation-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pinjam->praktik_id != null)
                                                @if ($pinjam->praktik_id == 1)
                                                    Peminjaman Ruang Lab dan Komputer <br>
                                                    <small>({{ $pinjam->ruang->nama }})</small>
                                                @else
                                                    Peminjaman Komputer
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal-konfirmasi-{{ $pinjam->id }}">
                                                Selesaikan
                                            </button>
                                            <button class="btn btn-danger"
                                                data-confirm="Hapus Peminjaman|Apakah anda yakin menghapus peminjaman ini?"
                                                data-confirm-yes="modalDelete({{ $pinjam->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <form action="{{ url('laboran/pengembalian-new/' . $pinjam->id) }}"
                                                method="POST" id="delete-{{ $pinjam->id }}">
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
                @else
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-md mb-0">
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Peminjam</th>
                                    <th>Waktu</th>
                                    <th>Praktik</th>
                                    <th class="text-center" style="width: 100px">Opsi</th>
                                </tr>
                                @forelse($pinjams as $pinjam)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ url('laboran/hubungi/' . $pinjam->peminjam_id) }}" target="_blank">
                                                {{ $pinjam->peminjam->nama }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($pinjam->praktik_id == '3')
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                                -
                                                <br>
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                            @else
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                                <br>
                                                {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                            @endif
                                            @php
                                                $now = Carbon\Carbon::now()->format('Y-m-d');
                                                $expire = date('Y-m-d', strtotime($pinjam->tanggal_awal));
                                            @endphp
                                            @if ($now > $expire)
                                                <i class="fas fa-exclamation-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pinjam->praktik_id != null)
                                                {{ $pinjam->praktik->nama }} <br>
                                                @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 4)
                                                    <small>({{ $pinjam->ruang->nama }})</small>
                                                @else
                                                    <small>({{ $pinjam->keterangan }})</small>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('laboran/pengembalian-new/' . $pinjam->id) }}"
                                                class="btn btn-primary rounded-0">
                                                Konfirmasi
                                            </a>
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
                @endif
            </div>
        </div>
    </section>
    @foreach ($pinjams as $pinjam)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-konfirmasi-{{ $pinjam->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Selesai</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Yakin selesaikan peminjaman?
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="{{ url('laboran/pengembalian-new/feb/' . $pinjam->id) }}" class="btn btn-primary">Ya</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        function modalDelete(id) {
            $("#delete-" + id).submit();
        }
    </script>
@endsection
