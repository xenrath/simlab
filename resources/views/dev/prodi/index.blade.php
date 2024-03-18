@extends('layouts.app')

@section('title', 'Data Prodi')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Prodi</h1>
            <div class="section-header-button">
                <a href="{{ url('dev/prodi/create') }}" class="btn btn-primary">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Prodi</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th>Kode</th>
                                            <th>Nama Prodi</th>
                                            <th>Tempat</th>
                                            <th class="text-center" style="width: 180px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prodis as $prodi)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $prodi->kode }}</td>
                                                <td>
                                                    {{ ucfirst($prodi->nama) }}
                                                    @if ($prodi->is_prodi)
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    @else
                                                        <i class="fas fa-exclamation-circle text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $prodi->tempat->nama ?? '' }}</td>
                                                <td class="text-center">
                                                    <form action="{{ url('dev/prodi/' . $prodi->id) }}" method="POST"
                                                        id="del-{{ $prodi->id }}">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="{{ url('dev/prodi/' . $prodi->id) }}"
                                                            class="btn btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ url('dev/prodi/' . $prodi->id . '/edit') }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="submit" class="btn btn-danger"
                                                            data-confirm="Hapus Data?|Yakin hapus prodi <b>{{ $prodi->nama }}</b>?"
                                                            data-confirm-yes="modalDelete({{ $prodi->id }})">
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
