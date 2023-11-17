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
                        <div class="card mb-3">
                            <div class="card-body">
                                <ul class="p-0" style="list-style: none">
                                    <li>
                                        <strong>{{ $pinjam->praktik->nama }}</strong>
                                    </li>
                                    <li>
                                        @if ($pinjam->praktik_id == '1')
                                            {{ $pinjam->ruang->nama }}
                                        @else
                                            {{ $pinjam->keterangan }}
                                        @endif
                                    </li>
                                    <li>
                                        <span class="text-muted">
                                            @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 2)
                                                {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                            @elseif ($pinjam->praktik_id == 3)
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                                {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
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
                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opsi
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ url('peminjam/bidan/menunggu/' . $pinjam->id) }}">Lihat</a>
                                        @if ($pinjam->peminjam_id == auth()->user()->id)
                                            <a class="dropdown-item"
                                                href="{{ url('peminjam/bidan/menunggu/' . $pinjam->id . '/edit') }}">Edit</a>
                                            <a class="dropdown-item" href="#"
                                                data-confirm="Hapus Peminjaman|Apakah anda yakin menghapus peminjaman ini?"
                                                data-confirm-yes="modalDelete({{ $pinjam->id }})">Hapus</a>
                                            <form action="{{ url('peminjam/bidan/menunggu/' . $pinjam->id) }}"
                                                method="POST" id="delete-{{ $pinjam->id }}">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <span class="text-muted">- Peminjaman menunggu tidak ada -</span>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            @if ($total > 6)
                <div class="justify-content-center bg-white rounded d-flex mt-3 pt-3">
                    {{ $pinjams->appends(Request::all())->links('pagination::simple-bootstrap-4') }}
                </div>
            @endif
        </div>
    </section>
    <script>
        function modalDelete(id) {
            $("#delete-" + id).submit();
        }
    </script>
@endsection
