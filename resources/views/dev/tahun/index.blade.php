@extends('layouts.app')

@section('title', 'Data Tahun')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tahun</h1>
            <div class="section-header-button">
                <button type="button" class="btn btn-primary rounded-0" data-toggle="modal"
                    data-target="#modal-tambah">Tambah</button>
            </div>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Tahun</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40px">No</th>
                                    <th>Nama Tahun</th>
                                    <th class="text-center" style="width: 120px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tahuns as $tahun)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($tahun->nama) }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning rounded-0" data-toggle="modal"
                                                data-target="#modal-edit-{{ $tahun->id }}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger rounded-0" data-toggle="modal"
                                                data-target="#modal-delete-{{ $tahun->id }}">
                                                <i class="fas fa-trash"></i>
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
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-4 border-bottom">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/tahun') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="nama">Nama Tahun</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control rounded-0 @if (!session('id')) @error('nama') is-invalid @enderror @endif"
                                value="{{ !session('id') ? old('nama') : null }}">
                            @if (!session('id'))
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between bg-whitesmoke">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-0">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($tahuns as $tahun)
        <div class="modal fade" id="modal-edit-{{ $tahun->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-4 border-bottom">
                        <h5 class="modal-title">Edit Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('dev/tahun/' . $tahun->id) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label for="nama">Nama Tahun</label>
                                <input type="text" name="nama" id="nama"
                                    class="form-control rounded-0 @if (session('id') == $tahun->id) @error('nama') is-invalid @enderror @endif"
                                    value="{{ session('id') == $tahun->id ? old('nama') : $tahun->nama }}">
                                @if (session('id') == $tahun->id)
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between bg-whitesmoke">
                            <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-0">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-delete-{{ $tahun->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-4 border-bottom">
                        <h5 class="modal-title">Hapus Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Yakin hapus Tahun <strong>{{ $tahun->nama }}</strong>?
                    </div>
                    <div class="modal-footer justify-content-between bg-whitesmoke">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('dev/tahun/' . $tahun->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger rounded-0">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
