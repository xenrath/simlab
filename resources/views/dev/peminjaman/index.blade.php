@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                    <div class="card-header-action dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle">Menu</a>
                        <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <li class="dropdown-title">Pilih Menu</li>
                            <li>
                                <a href="{{ url('dev/peminjaman/hapus_draft') }}" class="dropdown-item">Hapus Draft</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-4">
                        <form action="{{ url('dev/peminjaman') }}" method="GET" id="get-filter">
                            <div class="float-xs-right float-sm-right float-left mb-3" style="width: 140px">
                                <select class="form-control selectric" name="status"
                                    onchange="event.preventDefault(); document.getElementById('get-filter').submit();">
                                    <option value="">Semua</option>
                                    <option value="menunggu" {{ Request::get('status') == 'menunggu' ? 'selected' : '' }}>
                                        Menunggu</option>
                                    <option value="disetujui" {{ Request::get('status') == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="selesai" {{ Request::get('status') == 'selesai' ? 'selected' : '' }}>
                                        Selesai</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Peminjam</th>
                                    <th>Praktik</th>
                                    <th>Ruang / Tempat</th>
                                    <th class="text-center" style="width: 80px">Status</th>
                                    <th class="text-center" style="width: 20px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pinjams as $key => $pinjam)
                                    <tr>
                                        <td class="text-center">{{ $pinjams->firstItem() + $key }}</td>
                                        <td>
                                            {{ $pinjam->peminjam->nama }}<br>
                                            {{ $pinjam->peminjam->kode }}
                                        </td>
                                        @php
                                            $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                                            $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                                        @endphp
                                        <td>
                                            @if ($pinjam->peminjam->subprodi_id != '5')
                                                @if ($pinjam->praktik_id != null)
                                                    {{ $pinjam->praktik->nama }}<br>
                                                    @if ($pinjam->praktik_id == '1' || $pinjam->praktik_id == '2')
                                                        {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                                        {{ $tanggal_awal }}
                                                    @else
                                                        {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                                                    @endif
                                                @else
                                                    Praktik Laboratorium<br>
                                                    {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                                    {{ $tanggal_awal }}
                                                @endif
                                            @else
                                                @if ($pinjam->kategori == 'normal')
                                                    @if ($pinjam->praktik_id != null)
                                                        Praktik {{ ucfirst($pinjam->kategori) }} <br>
                                                        {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    Praktik {{ ucfirst($pinjam->kategori) }} <br>
                                                    {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                                    {{ $tanggal_awal }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pinjam->praktik_id == '1')
                                                {{ $pinjam->ruang->nama }}<br>
                                                {{-- {{ $pinjam->ruang->laboran->nama }} --}}
                                            @else
                                                {{ $pinjam->keterangan }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($pinjam->status == 'draft')
                                                <span class="badge badge-secondary">Draft</span>
                                            @elseif ($pinjam->status == 'menunggu')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif ($pinjam->status == 'disetujui')
                                                <span class="badge badge-primary">Disetujui</span>
                                            @elseif ($pinjam->status == 'selesai')
                                                <span class="badge badge-success">Selesai</span>
                                            @else
                                                <span class="badge badge-danger">{{ ucfirst($pinjam->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('dev/peminjaman/' . $pinjam->id) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="pagination float-right">
                        {{ $pinjams->appends(Request::all())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
