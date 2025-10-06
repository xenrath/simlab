@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman Menunggu</h1>
        </div>
        <div class="section-body">
            <div class="row">
                @forelse ($pinjams as $pinjam)
                    <div class="col-md-4">
                        <div class="card rounded-0 mb-3">
                            <div class="card-body">
                                <ul class="p-0" style="list-style: none">
                                    <li>
                                        <strong>{{ $pinjam->praktik->nama }}</strong>
                                    </li>
                                    <li>
                                        @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 4)
                                            {{ $pinjam->ruang->nama }}
                                        @else
                                            {{ $pinjam->keterangan }}
                                        @endif
                                    </li>
                                    <li>
                                        <span class="text-muted">
                                            @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 2 || $pinjam->praktik_id == 4)
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('l, d F Y') }}
                                                <br>
                                                {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB
                                            @elseif ($pinjam->praktik_id == 3)
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d F Y') }}
                                                -
                                                <br>
                                                {{ Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d F Y') }}
                                            @endif
                                        </span>
                                        @php
                                            $now = Carbon\Carbon::now()->format('Y-m-d');
                                            $expire = date('Y-m-d', strtotime($pinjam->tanggal_awal));
                                        @endphp
                                        @if ($now > $expire)
                                            <i class="fas fa-exclamation-circle text-danger"></i>
                                        @endif
                                    </li>
                                </ul>
                                <div class="btn-group">
                                    <button class="btn btn-info btn-sm rounded-0 dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opsi
                                    </button>
                                    <div class="dropdown-menu rounded-0">
                                        <a class="dropdown-item"
                                            href="{{ url('peminjam/perawat/menunggu/' . $pinjam->id) }}">Lihat</a>
                                        @if ($pinjam->peminjam_id == auth()->user()->id)
                                            @if ($pinjam->praktik_id != 4)
                                                <a class="dropdown-item"
                                                    href="{{ url('peminjam/perawat/menunggu/' . $pinjam->id . '/edit') }}">Edit</a>
                                            @endif
                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $pinjam->id }}">Hapus</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card rounded-0 mb-3">
                            <div class="card-body p-5 text-center">
                                <span class="text-muted">- Data tidak ditemukan -</span>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    @foreach ($pinjams as $pinjam)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus-{{ $pinjam->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Peminjaman</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span>Apakah anda yakin akan menghapus peminjaman ini?</span>
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('peminjam/perawat/menunggu/' . $pinjam->id) }}" method="POST"
                            id="form-hapus-{{ $pinjam->id }}">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-danger rounded-0" id="btn-hapus-{{ $pinjam->id }}"
                                onclick="form_hapus({{ $pinjam->id }})">
                                <div id="btn-hapus-load-{{ $pinjam->id }}" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-hapus-text-{{ $pinjam->id }}">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('script')
    <script>
        function form_hapus(id) {
            $('#btn-hapus-' + id).prop('disabled', true);
            $('#btn-hapus-text-' + id).hide();
            $('#btn-hapus-load-' + id).show();
            $('#form-hapus-' + id).submit();
        }
    </script>
@endsection