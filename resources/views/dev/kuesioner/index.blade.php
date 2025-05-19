@extends('layouts.app')

@section('title', 'Data Kuesioner')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Kuesioner</h1>
            <div class="section-header-button">
                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">Tambah</button>
            </div>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">Error!</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="px-3 m-0">
                        @foreach (session('error') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Kuesioner</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Judul</th>
                                            <th>Urutan Grafik</th>
                                            <th class="text-center" style="width: 120px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kuesioners as $key => $kuesioner)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $kuesioner->judul }}</td>
                                                <td>{{ ucfirst($kuesioner->urutan) }}</td>
                                                <td class="text-center">
                                                    <a href="{{ url('dev/kuesioner/' . $kuesioner->id . '/edit') }}"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#modal-hapus-{{ $kuesioner->id }}">
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
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-tambah">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kuesioner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('dev/kuesioner') }}" method="POST">
                    @csrf
                    <div class="modal-body pb-0">
                        <div class="form-group mb-3">
                            <label for="judul">Judul</label>
                            <textarea class="form-control" id="judul" name="judul" cols="30" rows="10" style="height: 120px;">{{ old('judul') }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="singkatan">Singkatan</label>
                            <input type="text" class="form-control" id="singkatan" name="singkatan"
                                value="{{ old('singkatan') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="urutan">Urutan Grafik</label>
                            <select class="custom-select custom-select-sm" name="urutan" id="urutan">
                                <option value="">Pilih</option>
                                <option value="pertanyaan" {{ old('urutan') == 'pertanyaan' ? 'selected' : '' }}>Pertanyaan
                                </option>
                                <option value="prodi" {{ old('urutan') == 'prodi' ? 'selected' : '' }}>Prodi</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach ($kuesioners as $kuesioner)
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus-{{ $kuesioner->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Kuesioner</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ url('dev/kuesioner/' . $kuesioner->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <div class="modal-body">
                            <p>Yakin hapus Kuesioner?</p>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
