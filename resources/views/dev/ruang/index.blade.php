@extends('layouts.app')

@section('title', 'Data Ruang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Ruang</h1>
            <div class="section-header-button">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah</button>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Ruang</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                <form action="{{ url('dev/ruang') }}" method="GET" id="get-filter">
                                    <div class="float-right mb-3 mr-3" style="width: 140px">
                                        <select class="form-control selectric" name="prodi_id"
                                            onchange="event.preventDefault();
                  document.getElementById('get-filter').submit();">
                                            <option value="">- Pilih -</option>
                                            @foreach ($prodis as $prodi)
                                                <option value="{{ $prodi->id }}"
                                                    {{ Request::get('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                                    {{ ucfirst($prodi->singkatan) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th>Ruang</th>
                                            <th>Laboran</th>
                                            <th>Prodi</th>
                                            <th class="text-center" style="width: 120px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ruangs as $key => $ruang)
                                            <tr>
                                                <td class="text-center">{{ $ruangs->firstItem() + $key }}</td>
                                                <td>
                                                    {{ $ruang->nama }}
                                                    @if ($ruang->is_praktik)
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    @else
                                                        <i class="fas fa-exclamation-circle text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $ruang->laboran->nama ?? '-' }}
                                                </td>
                                                <td>{{ ucfirst($ruang->prodi->singkatan) }}</td>
                                                <td class="text-center">
                                                    <form action="{{ url('dev/ruang/' . $ruang->id) }}" method="POST"
                                                        id="del-{{ $ruang->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{ url('dev/ruang/' . $ruang->id . '/edit') }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Apakah anda yakin menghapus ruangan <b>{{ $ruang->nama }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $ruang->id }})">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($ruangs->total() > 10)
                            <div class="card-footer">
                                <div class="pagination float-right">
                                    {{ $ruangs->appends(Request::all())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-tambah">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Ruang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/ruang/create') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label for="is_praktik">Untuk Praktik</label>
                            <select class="custom-select custom-select-sm" name="is_praktik">
                                <option value="">- Pilih -</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function modalDelete(id) {
            $("#del-" + id).submit();
        }
    </script>
@endsection
