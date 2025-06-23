@extends('layouts.app')

@section('title', 'Data Ruang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Ruang</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Ruang</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>Ruang Lab</th>
                                    <th>Laboran</th>
                                    <th class="text-center" style="width: 40px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ruangs as $key => $ruang)
                                    <tr>
                                        <td class="text-center">{{ $ruangs->firstItem() + $key }}</td>
                                        <td>{{ $ruang->nama }}</td>
                                        <td>{{ $ruang->laboran->nama ?? '-' }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info rounded-0" data-toggle="modal"
                                                data-target="#modal-detail-{{ $ruang->id }}">
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
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-detail-{{ $ruang->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Ruang</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Nama Ruang</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $ruang->nama }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Tempat</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $ruang->tempat->nama }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Laboran</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $ruang->laboran->nama ?? '-' }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Prodi</strong>
                            </div>
                            <div class="col-md-6">
                                {{ ucfirst($ruang->prodi->singkatan) }}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Untuk Praktik</strong>
                            </div>
                            <div class="col-md-6">
                                @if ($ruang->is_praktik)
                                    <span class="badge badge-primary">Ya</span>
                                @else
                                    <span class="badge badge-warning">Tidak</span>
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
