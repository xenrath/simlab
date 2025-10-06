@extends('layouts.app')

@section('title', 'Data Berita')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Berita</h1>
            <div class="section-header-button">
                <a href="{{ url('web/berita/create') }}" class="btn btn-primary rounded-0">Tambah</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Berita</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-md mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th class="text-center" style="width: 200px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($beritas as $berita)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ date('d M Y', strtotime($berita->created_at)) }}</td>
                                        <td>{{ $berita->judul }}</td>
                                        <td class="text-center">
                                            <form action="{{ url('web/berita/' . $berita->id) }}" method="POST"
                                                id="del-{{ $berita->id }}">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ url('web/berita/' . $berita->id) }}"
                                                    class="btn btn-info rounded-0">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ url('web/berita/' . $berita->id . '/edit') }}"
                                                    class="btn btn-warning rounded-0">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <button type="submit" class="btn btn-danger rounded-0"
                                                    data-confirm="Hapus Data?|Apakah anda yakin menghapus berita <b>{{ $berita->judul }}</b>?"
                                                    data-confirm-yes="modalDelete({{ $berita->id }})">
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
                        <div class="pagination">
                            {{ $beritas->appends(Request::all())->links('pagination::bootstrap-4') }}
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
