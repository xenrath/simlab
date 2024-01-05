@extends('layouts.app')

@section('title', 'Data Subprodi')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Subprodi</h1>
            <div class="section-header-button">
                <a href="{{ url('dev/subprodi/create') }}" class="btn btn-primary">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Subprodi</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Jenjang</th>
                                    <th>Nama Prodi</th>
                                    <th class="text-center" style="width: 120px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="data-barang">
                                @forelse ($subprodis as $subprodi)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $subprodi->jenjang }}</td>
                                        <td>{{ $subprodi->nama }}</td>
                                        <td class="text-center">
                                            <form action="{{ url('dev/subprodi/' . $subprodi->id) }}" method="POST"
                                                id="del-{{ $subprodi->id }}">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ url('dev/subprodi/' . $subprodi->id . '/edit') }}"
                                                    class="btn btn-warning">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger"
                                                    data-confirm="Hapus Data?|Yakin hapus subprodi <b>{{ $subprodi->nama }}</b>?"
                                                    data-confirm-yes="modalDelete({{ $subprodi->id }})">
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
    </section>
    <script>
        function modalDelete(id) {
            $("#del-" + id).submit();
        }
    </script>
@endsection
