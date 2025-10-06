@extends('layouts.app')

@section('title', 'Data Ruang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Ruang</h1>
            <div class="section-header-button">
                <a href="{{ url('admin/ruang/create') }}" class="btn btn-primary rounded-0">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card rounded-0 mb-3">
                        <div class="card-header">
                            <h4>Data Ruang</h4>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-end">
                                <div class="col-md-3">
                                    <select class="form-control rounded-0" name="prodi_id"
                                        onchange="event.preventDefault(); document.getElementById('get-filter').submit();">
                                        <option value="">- Pilih -</option>
                                        @foreach ($prodis as $prodi)
                                            <option value="{{ $prodi->id }}"
                                                {{ Request::get('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                                {{ ucfirst($prodi->singkatan) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
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
                                                    <a href="{{ url('admin/ruang/' . $ruang->id . '/edit') }}"
                                                        class="btn btn-warning rounded-0">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <button type="submit" class="btn btn-danger rounded-0"
                                                        data-toggle="modal" data-target="#modal-hapus-{{ $ruang->id }}">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
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
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $ruangs->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach ($ruangs as $ruang)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus-{{ $ruang->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Ruang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span>Apakah anda yakin akan menghapus ruang ini?</span>
                    </div>
                    <div class="modal-footer bg-whitesmoke justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('admin/ruang/' . $ruang->id) }}" method="POST"
                            id="form-hapus-{{ $ruang->id }}">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-danger rounded-0" id="btn-hapus-{{ $ruang->id }}"
                                onclick="form_hapus({{ $ruang->id }})">
                                <div id="btn-hapus-load-{{ $ruang->id }}" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-hapus-text-{{ $ruang->id }}">Hapus</span>
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
