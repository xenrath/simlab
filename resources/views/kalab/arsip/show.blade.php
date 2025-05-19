@extends('layouts.app')

@section('title', 'Data Arsip')

@section('content')
    <section class="section">
        <div class="section-header align-middle">
            <div class="section-header-back">
                <a href="{{ url('kalab/arsip') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Data Arsip</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card rounded-0">
                        <div class="card-header">
                            <h4>Data Arsip Tahun {{ $tahun }}</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modal-tambah">Tambah</button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered table-hover table-md">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px">No.</th>
                                        <th>
                                            Nama
                                            <span class="d-none d-md-inline">Arsip</span>
                                        </th>
                                        <th class="text-center" style="width: 180px">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($arsips as $arsip)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $arsip->nama }}</td>
                                            <td class="text-center">
                                                <a href="{{ asset('storage/uploads/' . $arsip->file) }}"
                                                    class="btn btn-info rounded-0" target="_blank">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-warning rounded-0" data-toggle="modal"
                                                    data-target="#modal-edit-{{ $arsip->id }}">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger rounded-0" data-toggle="modal"
                                                    data-target="#modal-hapus-{{ $arsip->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-4 border-bottom">
                    <h5 class="modal-title">Tambah Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('kalab/arsip') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="nama">Nama Berkas</label>
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
                        <div class="form-group mb-2">
                            <label for="tahun">Tahun</label>
                            <input type="text" name="tahun" id="tahun" class="form-control rounded-0"
                                value="{{ $tahun }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="file">
                                File
                                <small>(maksimal 2MB)</small>
                            </label>
                            <input type="file" name="file" id="file"
                                class="form-control rounded-0 @if (!session('id')) @error('file') is-invalid @enderror @endif"
                                accept=".pdf" value="{{ !session('id') ? old('file') : null }}">
                            @if (!session('id'))
                                @error('file')
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
    @foreach ($arsips as $arsip)
        <div class="modal fade" id="modal-edit-{{ $arsip->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-4 border-bottom">
                        <h5 class="modal-title">Edit Arsip</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('kalab/arsip/' . $arsip->id) }}" method="POST" enctype="multipart/form-data"
                        autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label for="nama">Nama Berkas</label>
                                <input type="text" name="nama" id="nama"
                                    class="form-control rounded-0 @if (session('id') == $arsip->id) @error('nama') is-invalid @enderror @endif"
                                    value="{{ session('id') == $arsip->id ? old('nama') : $arsip->nama }}">
                                @if (session('id') == $arsip->id)
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                @endif
                            </div>
                            <div class="form-group mb-2">
                                <label for="tahun">Tahun</label>
                                <input type="text" name="tahun" id="tahun" class="form-control rounded-0"
                                    value="{{ $arsip->tahun }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="file">
                                    File
                                    <small>(opsional | maksimal 2MB)</small>
                                </label>
                                <input type="file" name="file" id="file"
                                    class="form-control rounded-0 @if (session('id') == $arsip->id) @error('file') is-invalid @enderror @endif"
                                    accept=".pdf" value="{{ old('file') }}">
                                @if (session('id') == $arsip->id)
                                    @error('file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between bg-whitesmoke">
                            <button type="button" class="btn btn-secondary rounded-0"
                                data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-0">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-hapus-{{ $arsip->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header pb-4 border-bottom">
                        <h5 class="modal-title">Hapus Arsip</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Yakin menghapus data arsip <strong>{{ $arsip->nama }}</strong>?
                    </div>
                    <div class="modal-footer justify-content-between bg-whitesmoke">
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                        <form action="{{ url('kalab/arsip/' . $arsip->id) }}" method="POST">
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
