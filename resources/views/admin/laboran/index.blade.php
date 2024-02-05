@extends('layouts.app')

@section('title', 'Data Laboran')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Laboran</h1>
            <div class="section-header-button">
                <a href="{{ url('admin/laboran/create') }}" class="btn btn-primary">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Laboran</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Nama Laboran</th>
                                            <th>Prodi</th>
                                            <th class="text-center" style="width: 180px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $key => $user)
                                            <tr>
                                                <td class="text-center">{{ $users->firstItem() + $key }}</td>
                                                <td>{{ $user->nama }}</td>
                                                <td>
                                                    @if ($user->prodi_id)
                                                        {{ ucfirst($user->prodi->singkatan) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <form action="{{ url('admin/laboran/' . $user->id) }}"
                                                        method="POST" id="del-{{ $user->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{ url('admin/laboran/' . $user->id) }}"
                                                            class="btn btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ url('admin/laboran/' . $user->id . '/edit') }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Apakah anda yakin menghapus <b>{{ $user->nama }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $user->id }})">
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
                        @if ($users->total() > 10)
                            <div class="card-footer">
                                <div class="pagination float-right">
                                    {{ $users->appends(Request::all())->links('pagination::bootstrap-4') }}
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
                <form action="{{ url('admin/laboran/import') }}" method="POST" enctype="multipart/form-data">
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
