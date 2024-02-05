@extends('layouts.app')

@section('title', 'Data Bahan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Bahan</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Bahan</h4>
                </div>
                <div class="card-body p-0">
                    <form action="{{ url('kalab/bahan/habis') }}" method="get" id="get-kategori">
                        <div class="row pt-3 px-4">
                            <div class="col-0 col-md-9"></div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group">
                                    <input type="search" class="form-control" name="keyword" placeholder="Cari"
                                        value="{{ Request::get('keyword') }}" autocomplete="off"
                                        onsubmit="event.preventDefault(); document.getElementById('get-keyword').submit();">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Nama Bahan</th>
                                    <th>Tempat</th>
                                    <th>Ruang Lab</th>
                                    <th class="text-center" style="width: 40px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="data-barang">
                                @forelse ($bahans as $key => $bahan)
                                    <tr>
                                        <td class="text-center">{{ $bahans->firstItem() + $key }}</td>
                                        <td>{{ $bahan->nama }}</td>
                                        <td>{{ $bahan->ruang->tempat->nama }}</td>
                                        <td>{{ $bahan->ruang->nama }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('kalab/bahan/' . $bahan->id) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($bahans->total() > 10)
                            <div class="pagination px-4 py-2 d-flex justify-content-md-end">
                                {{ $bahans->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
