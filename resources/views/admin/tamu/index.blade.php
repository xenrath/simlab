@extends('layouts.app')

@section('title', 'Data Tamu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tamu</h1>
            <div class="section-header-button">
                <a href="{{ url('admin/tamu/create') }}" class="btn btn-primary">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Tamu</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="px-4 py-3">
                                <form action="{{ url('admin/tamu') }}" method="GET" id="get-filter">
                                    <div class="float-xs-right float-sm-right float-left mb-3">
                                        <div class="input-group">
                                            <input type="search" class="form-control" id="keyword" name="keyword"
                                                placeholder="masukan nama tamu" value="{{ Request::get('keyword') }}"
                                                autocomplete="off"
                                                onsubmit="event.preventDefault(); document.getElementById('get-filter').submit();">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Nama Tamu</th>
                                            <th>Instansi</th>
                                            <th class="text-center" style="width: 180px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tamus as $key => $tamu)
                                            <tr>
                                                <td class="text-center">{{ $tamus->firstItem() + $key }}</td>
                                                <td>{{ $tamu->nama }}</td>
                                                <td>
                                                    {{ $tamu->institusi }}
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ url('admin/tamu/' . $tamu->id) }}"
                                                        method="POST" id="del-{{ $tamu->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{ url('admin/tamu/' . $tamu->id) }}"
                                                            class="btn btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ url('admin/tamu/' . $tamu->id . '/edit') }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Apakah anda yakin menghapus <b>{{ $tamu->nama }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $tamu->id }})">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">- Data tidak ditemukan -</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($tamus->total() > 10)
                            <div class="card-footer float-right">
                                <div class="float-right">
                                    {{ $tamus->appends(Request::all())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalImport">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('admin/tamu/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">File</label>
                            <input type="file" class="form-control" id="file" name="file"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
