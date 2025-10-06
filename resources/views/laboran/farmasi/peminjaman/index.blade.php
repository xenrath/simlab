@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman Menunggu</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Peminjaman</h4>
                </div>
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
                                        @if ($pinjam->kategori == 'normal')
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                            -
                                            <br>
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                        @elseif ($pinjam->kategori == 'estafet')
                                            {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                            <br>
                                            {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                        @endif
                                        @php
                                            $now = Carbon\Carbon::now()->format('Y-m-d');
                                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_akhir));
                                        @endphp
                                        @if ($now > $expire)
                                            <i class="fas fa-exclamation-circle text-danger"></i>
                                        @endif
                                    </td>
                                    <td>
                                        Praktik {{ $pinjam->kategori == 'normal' ? 'Mandiri' : 'Estafet' }} <br>
                                        <small>({{ $pinjam->ruang->nama }})</small>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ url('laboran/farmasi/peminjaman/setujui/' . $pinjam->id) }}"
                                            method="get" id="form-konfirmasi-{{ $pinjam->id }}">
                                            <a href="{{ url('laboran/farmasi/peminjaman/' . $pinjam->id) }}"
                                                class="btn btn-info rounded-0">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-primary rounded-0"
                                                id="btn-konfirmasi-{{ $pinjam->id }}"
                                                onclick="form_konfirmasi({{ $pinjam->id }})">
                                                <i class="fas fa-spinner fa-spin"
                                                    id="btn-konfirmasi-load-{{ $pinjam->id }}"
                                                    style="display: none;"></i>
                                                <i class="fas fa-check" id="btn-konfirmasi-icon-{{ $pinjam->id }}"></i>
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
