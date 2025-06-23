@extends('layouts.app')

@section('title', 'Data Laboran')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Laboran</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Laboran</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-md mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>Nama Laboran</th>
                                    <th>Ruang Lab</th>
                                    <th class="text-center" style="width: 40px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $key => $user)
                                    <tr>
                                        <td class="text-center">{{ $users->firstItem() + $key }}</td>
                                        <td>{{ $user->nama }}</td>
                                        <td>
                                            @if (count($user->ruangs) > 0)
                                                <ul class="px-3 mb-0">
                                                    @foreach ($user->ruangs as $ruang)
                                                        <li>{{ $ruang->nama }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <small>(belum ada ruang lab yang dikaitkan)</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info rounded-0" data-toggle="modal" data-target="#modal-detail-{{ $user->id }}">
                                                <i class="fas fa-eye"></i>
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
                        @if ($users->total() > 10)
                            <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                                {{ $users->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @foreach ($users as $user)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-detail-{{ $user->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Laboran</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Nama Laboran</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $user->nama }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Laboran Prodi</strong>
                            </div>
                            <div class="col-md-6">
                                {{ ucfirst($user->prodi->singkatan) }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>No. Telepon</strong>
                            </div>
                            <div class="col-md-6">
                                @if ($user->telp)
                                    <a href="{{ url('kalab/hubungi_user/' . $user->id) }}" target="_blank">
                                        {{ $user->telp }}
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Alamat</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $user->alamat ?? '-' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Ruang Lab</strong>
                            </div>
                            <div class="col-md-6">
                                @if (count($user->ruangs) > 0)
                                    <ul class="px-3 mb-0">
                                        @foreach ($user->ruangs as $ruang)
                                            <li>{{ $ruang->nama }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <small>(tidak ada ruang lab yang dikaitkan)</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br justify-content-start">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
