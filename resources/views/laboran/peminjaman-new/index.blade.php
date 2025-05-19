@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman Menunggu</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                </div>
                @if (auth()->user()->prodi_id == '7')
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-md">
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Peminjam</th>
                                    <th>Waktu</th>
                                    <th>Kategori</th>
                                    <th style="width: 120px">Opsi</th>
                                </tr>
                                @forelse($pinjams as $key => $pinjam)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ url('laboran/hubungi/' . $pinjam->peminjam_id) }}" target="_blank">
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
                                            @if ($pinjam->praktik_id == '3')
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
                                                @if ($pinjam->praktik_id == 1)
                                                    Peminjaman Ruang Lab dan Komputer <br>
                                                    ({{ $pinjam->ruang->nama }})
                                                @else
                                                    Peminjaman Komputer
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('laboran/peminjaman-new/' . $pinjam->id) }}"
                                                class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-danger"
                                                data-confirm="Hapus Peminjaman|Apakah anda yakin menghapus peminjaman ini?"
                                                data-confirm-yes="modalDelete({{ $pinjam->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <form action="{{ url('laboran/peminjaman-new/' . $pinjam->id) }}"
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
                                    <th class="text-center" style="width: 120px">Opsi</th>
                                </tr>
                                @forelse($pinjams as $key => $pinjam)
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
                                        <td class="text-center">
                                            <form action="{{ url('laboran/peminjaman-new/setujui/' . $pinjam->id) }}"
                                                method="get" id="form-konfirmasi-{{ $pinjam->id }}">
                                                <a href="{{ url('laboran/peminjaman-new/' . $pinjam->id) }}"
                                                    class="btn btn-info rounded-0">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-primary rounded-0"
                                                    id="btn-konfirmasi-{{ $pinjam->id }}"
                                                    onclick="form_konfirmasi({{ $pinjam->id }})">
                                                    <i class="fas fa-spinner fa-spin"
                                                        id="btn-konfirmasi-load-{{ $pinjam->id }}"
                                                        style="display: none;"></i>
                                                    <i class="fas fa-check"
                                                        id="btn-konfirmasi-icon-{{ $pinjam->id }}"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted" colspan="5">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function form_konfirmasi(id) {
            $('#btn-konfirmasi-' + id).prop('disabled', true);
            $('#btn-konfirmasi-icon-' + id).hide();
            $('#btn-konfirmasi-load-' + id).show();
            $('#form-konfirmasi-' + id).submit();
        }
    </script>
@endsection
