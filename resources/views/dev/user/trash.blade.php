@extends('layouts.app')

@section('title', 'Data User')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/user') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Sampah User</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data User</h4>
                            <div class="card-header-action">
                                <a href="{{ url('dev/user/restore') }}" class="btn btn-info btn-sm">
                                    Pulihkan Semua
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#modal-hapus">
                                    Hapus Semua
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 40px">No</th>
                                            <th>Nama</th>
                                            <th>Role</th>
                                            <th class="text-center" style="width: 180px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-wrap">{{ $user->nama }}</td>
                                                <td>{{ ucfirst($user->role) }}</td>
                                                <td class="text-center">
                                                    <a href="{{ url('dev/user/trash/' . $user->id) }}" class="btn btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ url('dev/user/restore/' . $user->id) }}"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-undo"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#modal-hapus-{{ $user->id }}">
                                                        <i class="fas fa-times"></i>
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
    @foreach ($users as $user)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus-{{ $user->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Yakin hapus sampah user <strong>{{ $user->nama }}</strong>?
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <a href="{{ url('dev/user/delete/' . $user->id) }}" class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Yakin hapus semua sampah user?
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="{{ url('dev/user/delete') }}" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>
@endsection
