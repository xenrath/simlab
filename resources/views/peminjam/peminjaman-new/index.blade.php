@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Peminjaman</h1>
            <div class="section-header-button">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-praktik">Buat
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
                                        <span
                                            class="text-muted">{{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}</span>
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
                                                href="{{ url('peminjam/normal/peminjaman-new/' . $pinjam->id) }}">Lihat</a>
                                            @if ($pinjam->status == 'menunggu')
                                                <a class="dropdown-item"
                                                    href="{{ url('peminjam/normal/peminjaman-new/' . $pinjam->id . '/edit') }}">Edit</a>
                                                <a class="dropdown-item" href="#"
                                                    data-confirm="Hapus Peminjaman|Apakah anda yakin menghapus peminjaman ini?"
                                                    data-confirm-yes="modalDelete({{ $pinjam->id }})">Hapus</a>
                                                <form action="{{ url('peminjam/normal/peminjaman-new/' . $pinjam->id) }}"
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
            @if (count($pinjams) > 9)
                <div class="card">
                    <div class="card-body">
                        <div class="paginate">
                            {{ $pinjams->appends(Request::all())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <div class="modal fade" id="modal-praktik" tabindex="-1" role="dialog" aria-labelledby="modal-praktik"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kategori Praktik</h5>
                </div>
                <form action="{{ url('peminjam/normal/peminjaman-new/praktik') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <select name="praktik_id" id="praktik_id" class="custom-select custom-select-sm">
                                <option value="">- Pilih -</option>
                                @foreach ($praktiks as $praktik)
                                    <option value="{{ $praktik->id }}">{{ $praktik->nama }}</option>
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
