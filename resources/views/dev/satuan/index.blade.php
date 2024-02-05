@extends('layouts.app')

@section('title', 'Data Satuan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Satuan</h1>
            <div class="section-header-button">
                <a href="{{ url('dev/satuan/create') }}" class="btn btn-primary">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Satuan</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40px">No.</th>
                                    <th>Nama Satuan</th>
                                    <th>Singkatan</th>
                                    <th>Kali</th>
                                    <th>Kategori</th>
                                    <th class="text-center" style="width: 120px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="data-barang">
                                @forelse ($satuans as $key => $satuan)
                                    <tr>
                                        <td class="text-center">{{ $satuans->firstItem() + $key }}</td>
                                        <td>{{ $satuan->nama }}</td>
                                        <td>{{ $satuan->singkatan }}</td>
                                        <td>{{ $satuan->kali }}</td>
                                        <td>{{ $satuan->kategori }}</td>
                                        <td class="text-center">
                                            <form action="{{ url('dev/satuan/' . $satuan->id) }}" method="POST"
                                                id="del-{{ $satuan->id }}">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ url('dev/satuan/' . $satuan->id . '/edit') }}"
                                                    class="btn btn-warning">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger"
                                                    data-confirm="Hapus Data?|Apakah anda yakin menghapus satuan <b>{{ $satuan->nama }}</b>?"
                                                    data-confirm-yes="modalDelete({{ $satuan->id }})">
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
                @if ($satuans->total() > 10)
                    <div class="card-footer">
                        <div class="pagination float-right">
                            {{ $satuans->appends(Request::all())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
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
