@extends('layouts.app')

@section('title', 'Edit Kuesioner')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dev/kuesioner') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Kuesioner</h1>
        </div>
        <div class="section-body">
            @if (session('error_kuesioner'))
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <div class="alert-title">Error!</div>
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <ul class="px-3 m-0">
                            @foreach (session('error_kuesioner') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Kuesioner</h4>
                    <div class="card-header-action">
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#modal-edit-judul">
                            Edit
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Judul</strong>
                        <br>
                        {{ $kuesioner->judul }}
                    </p>
                    <p>
                        <strong>Singkatan</strong>
                        <br>
                        {{ $kuesioner->singkatan }}
                    </p>
                    <p>
                        <strong>Urutan</strong>
                        <br>
                        {{ ucfirst($kuesioner->urutan) }}
                    </p>
                </div>
            </div>
            @if (session('error_pertanyaan'))
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <div class="alert-title">Error!</div>
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <ul class="px-3 m-0">
                            @foreach (session('error_pertanyaan') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Pertanyaan</h4>
                    <div class="card-header-action">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modal-tambah-pertanyaan">
                            Tambah
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40px">No</th>
                                    <th>Pertanyaan</th>
                                    <th class="text-center" style="width: 120px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pertanyaan_kuesioners as $key => $pertanyaan_kuesioner)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $pertanyaan_kuesioner->pertanyaan }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#modal-edit-pertanyaan-{{ $pertanyaan_kuesioner->id }}">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#modal-hapus-pertanyaan-{{ $pertanyaan_kuesioner->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-edit-judul">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kuesioner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/kuesioner/' . $kuesioner->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-body pb-0">
                        <div class="form-group mb-3">
                            <label for="judul">Judul</label>
                            <textarea class="form-control" id="judul" name="judul" cols="30" rows="10" style="height: 120px;">{{ old('judul', $kuesioner->judul) }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="singkatan">Singkatan</label>
                            <input type="text" class="form-control" id="singkatan" name="singkatan"
                                value="{{ old('singkatan', $kuesioner->singkatan) }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="urutan">Urutan Grafik</label>
                            <select class="custom-select custom-select-sm" name="urutan" id="urutan">
                                <option value="">Pilih</option>
                                <option value="pertanyaan" {{ old('urutan', $kuesioner->urutan) == 'pertanyaan' ? 'selected' : '' }}>Pertanyaan
                                </option>
                                <option value="prodi" {{ old('urutan', $kuesioner->urutan) == 'prodi' ? 'selected' : '' }}>Prodi</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-tambah-pertanyaan">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pertanyaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/pertanyaan-kuesioner') }}" method="post">
                    @csrf
                    <div class="modal-body pb-0">
                        <div class="form-group mb-3">
                            <label for="pertanyaan">Pertanyaan</label>
                            <input type="hidden" class="form-control" name="kuesioner_id"
                                value="{{ $kuesioner->id }}">
                            <textarea class="form-control" id="pertanyaan" name="pertanyaan" cols="30" rows="10"
                                style="height: 120px;">{{ old('pertanyaan') }}</textarea>
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
    @foreach ($pertanyaan_kuesioners as $pertanyaan_kuesioner)
        <div class="modal fade" tabindex="-1" role="dialog"
            id="modal-edit-pertanyaan-{{ $pertanyaan_kuesioner->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pertanyaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('dev/pertanyaan-kuesioner/' . $pertanyaan_kuesioner->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="modal-body pb-0">
                            <div class="form-group mb-3">
                                <label for="pertanyaan">Pertanyaan</label>
                                <input type="hidden" class="form-control" name="kuesioner_id"
                                    value="{{ $kuesioner->id }}">
                                <textarea class="form-control" id="pertanyaan" name="pertanyaan" cols="30" rows="10"
                                    style="height: 120px;">{{ $pertanyaan_kuesioner->pertanyaan }}</textarea>
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
        <div class="modal fade" tabindex="-1" role="dialog"
            id="modal-hapus-pertanyaan-{{ $pertanyaan_kuesioner->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Pertanyaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('dev/pertanyaan-kuesioner/' . $pertanyaan_kuesioner->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <div class="modal-body">
                            <p>Yakin hapus pertanyaan?</p>
                        </div>
                        <div class="modal-footer bg-whitesmoke">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
