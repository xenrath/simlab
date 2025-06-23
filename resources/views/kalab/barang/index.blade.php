@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Barang</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Barang</h4>
                    <div class="card-header-action">
                        {{-- <a href="{{ url('kalab/barang/hilang') }}" class="btn btn-warning btn-sm">
                            Detail Hilang
                            <i class="fas fa-chevron-right"></i>
                        </a> --}}
                        <a href="{{ url('kalab/barang/rusak') }}" class="btn btn-danger btn-sm">
                            Detail Rusak
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <form action="{{ url('kalab/barang') }}" method="get" id="form-search">
                        <div class="row justify-content-between pt-3 px-4 mb-2">
                            <div class="col-md-3 mb-2">
                                <select class="form-control rounded-0" name="prodi_id" onchange="search_get()">
                                    <option value="">Semua</option>
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->id }}"
                                            {{ Request::get('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                            {{ ucfirst($prodi->singkatan) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="input-group">
                                    <input type="search" class="form-control rounded-0" name="keyword"
                                        placeholder="cari nama" value="{{ Request::get('keyword') }}" autocomplete="off"
                                        onsubmit="event.preventDefault(); document.getElementById('form-search').submit();">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary rounded-0">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Nama Barang</th>
                                    <th>Ruang Lab</th>
                                    <th>Jumlah Rusak</th>
                                    <th>Jumlah Hilang</th>
                                    <th class="text-center" style="width: 20px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $key => $barang)
                                    <tr>
                                        <td class="text-center">{{ $barangs->firstItem() + $key }}</td>
                                        <td>{{ $barang->nama }}</td>
                                        <td>{{ $barang->ruang->nama }}</td>
                                        <td>{{ $barang->rusak ?? '0' }} Pcs</td>
                                        <td>{{ $barang->hilang ?? '0' }} Pcs</td>
                                        <td class="text-center">
                                            <a href="{{ url('kalab/barang/' . $barang->id) }}"
                                                class="btn btn-info rounded-0">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="pagination px-3 mt-4 mb-2 justify-content-md-end">
                            {{ $barangs->appends(Request::all())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
