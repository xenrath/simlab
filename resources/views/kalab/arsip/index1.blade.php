@extends('layouts.app')

@section('title', 'Data Arsip')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Arsip</h1>
            <div class="section-header-button">
                <a href="{{ url('kalab/arsip/create') }}" class="btn btn-primary">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Arsip</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th>Nama</th>
                                            <th class="text-center" style="width: 120px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($arsips as $arsip)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $arsip->nama }}</td>
                                                <td class="text-center">
                                                    <form action="{{ url('kalab/arsip/' . $arsip->id) }}" method="POST"
                                                        id="del-{{ $arsip->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{ asset('storage/uploads/' . $arsip->file) }}"
                                                            class="btn btn-info" target="_blank">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Apakah anda yakin menghapus arsip <b>{{ $arsip->judul }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $arsip->id }})">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="4">- Data tidak ditemukan -</td>
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
    <script>
        function modalDelete(id) {
            $("#del-" + id).submit();
        }
    </script>
@endsection
