@extends('layouts.app')

@section('title', 'Tagihan Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tagihan Peminjaman</h1>
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
                                        @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 4)
                                            {{ $pinjam->ruang->nama }}
                                        @else
                                            {{ $pinjam->keterangan }}
                                        @endif
                                    </li>
                                    <li>
                                        <span class="text-muted">
                                            @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 2 || $pinjam->praktik_id == 4)
                                                {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                            @elseif ($pinjam->praktik_id == 3)
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                                {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                                <div class="btn-group">
                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opsi
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{ url('peminjam/labterpadu/tagihan/' . $pinjam->id) }}">Lihat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <span class="text-muted">- Tagihan peminjaman tidak ada -</span>
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
