@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman</h1>
            <div class="section-header-button">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-kategori">Buat
                    Peminjaman</button>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                @foreach ($pinjams as $pinjam)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="p-0" style="list-style: none">
                                    @php
                                        if ($pinjam->kategori == 'normal') {
                                            $kategori = 'Mandiri';
                                        } else {
                                            $kategori = 'Estafet';
                                        }
                                    @endphp
                                    <li>
                                        <strong>{{ $pinjam->praktik->nama }} ({{ $kategori }})</strong>
                                    </li>
                                    <li>
                                        @if ($pinjam->praktik_id == '1')
                                            {{ $pinjam->ruang->nama }}
                                        @endif
                                    </li>
                                    <li>
                                        <span class="text-muted">
                                            @if ($pinjam->kategori == 'normal')
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }} -
                                                {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                                            @elseif ($pinjam->kategori == 'estafet')
                                                {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }},
                                                {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Opsi
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ url('peminjam/normal/peminjaman/' . $pinjam->id) }}">Lihat</a>
                                            @if ($pinjam->status == 'menunggu' || $pinjam->status == 'disetujui')
                                                <a class="dropdown-item"
                                                    href="{{ url('peminjam/normal/peminjaman/' . $pinjam->id . '/edit') }}">Edit</a>
                                            @endif
                                            @if ($pinjam->status == 'menunggu')
                                                <a class="dropdown-item" href="#"
                                                    data-confirm="Hapus Peminjaman|Apakah anda yakin menghapus peminjaman ini?"
                                                    data-confirm-yes="modalDelete({{ $pinjam->id }})">Hapus</a>
                                                <form action="{{ url('peminjam/normal/peminjaman/' . $pinjam->id) }}"
                                                    method="POST" id="delete-{{ $pinjam->id }}">
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($pinjam->status == 'menunggu')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif ($pinjam->status == 'disetujui')
                                        <span class="badge badge-primary">Proses</span>
                                    @elseif ($pinjam->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-kategori" tabindex="-1" role="dialog" aria-labelledby="modal-kategori"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title mb-3">Buat Peminjaman</h5>
                </div>
                <form action="{{ url('peminjam/normal/peminjaman/create') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Kategori Praktik</label>
                            <select name="kategori" id="kategori" class="custom-select custom-select-sm">
                                <option value="">- Pilih -</option>
                                <option value="estafet" {{ old('kategori') == 'estafet' ? 'selected' : null }}>Estafet
                                </option>
                                <option value="mandiri" {{ old('kategori') == 'mandiri' ? 'selected' : null }}>Mandiri
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Ruang Lab</label>
                            <select name="ruang_id" id="ruang_id" class="custom-select custom-select-sm">
                                <option value="">- Pilih -</option>
                                @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}"
                                        {{ old('ruang_id') == $ruang->id ? 'selected' : null }}>{{ $ruang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function modalDelete(id) {
            $("#delete-" + id).submit();
        }
    </script>
@endsection
