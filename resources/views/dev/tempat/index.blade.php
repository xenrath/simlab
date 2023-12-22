@extends('layouts.app')

@section('title', 'Data Tempat')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tempat</h1>
            <div class="section-header-button">
                <a href="{{ url('dev/tempat/create') }}" class="btn btn-primary">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Tempat</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th>Kode</th>
                                            <th>Nama Tempat</th>
                                            <th class="text-center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tempats as $tempat)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $tempat->kode }}</td>
                                                <td>{{ $tempat->nama }}</td>
                                                <td class="text-center w-25">
                                                    <form action="{{ url('dev/tempat/' . $tempat->id) }}" method="POST"
                                                        id="del-{{ $tempat->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{ url('dev/tempat/' . $tempat->id . '/edit') }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Apakah anda yakin menghapus tempat <b>{{ $tempat->nama }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $tempat->id }})">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
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
            </div>
        </div>
    </section>
    <script>
        function modalDelete(id) {
            $("#del-" + id).submit();
        }
    </script>
@endsection
