@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Barang</h1>
        </div>
        @if (session('failures'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <div class="alert-title">Error</div>
                </div>
                @foreach (session('failures') as $fail)
                    <p>
                        <span class="bullet"></span>&nbsp;
                        Baris ke {{ $fail->row() }} : <strong>{{ $fail->values()[$fail->attribute()] }}</strong>,
                        @foreach ($fail->errors() as $error)
                            {{ $error }}
                        @endforeach
                    </p>
                @endforeach
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">Gagal !</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="px-3 mb-0">
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
                            <h4>Data Barang</h4>
                            <div class="card-header-action">
                                <a href="{{ url('kalab/barang/hilang') }}" class="btn btn-warning btn-sm">
                                    Detail Hilang
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                                <a href="{{ url('kalab/barang/rusak') }}" class="btn btn-danger btn-sm">
                                    Detail Rusak
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <form action="{{ url('kalab/barang') }}" method="get" id="get-filter">
                                <div class="row pt-3 px-4">
                                    <div class="col-md-3 mb-3">
                                        <select class="custom-select custom-select-sm" name="prodi_id"
                                            onchange="event.preventDefault(); document.getElementById('get-filter').submit();">
                                            <option value="">Semua</option>
                                            @foreach ($prodis as $prodi)
                                                <option value="{{ $prodi->id }}"
                                                    {{ Request::get('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                                    {{ ucfirst($prodi->singkatan) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-0 col-md-6"></div>
                                    <div class="col-md-3 mb-3">
                                        <div class="input-group">
                                            <input type="search" class="form-control" name="keyword" placeholder="cari nama"
                                                value="{{ Request::get('keyword') }}" autocomplete="off"
                                                onsubmit="event.preventDefault(); document.getElementById('get-filter').submit();">
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
                                <table class="table table-bordered table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th>Nama Barang</th>
                                            <th>Ruang Lab</th>
                                            <th>Jumlah Rusak</th>
                                            <th>Jumlah Hilang</th>
                                            <th class="text-center" style="width: 40px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barangs as $key => $barang)
                                            <tr>
                                                <td class="text-center">{{ $barangs->firstItem() + $key }}</td>
                                                <td>{{ $barang->nama }}</td>
                                                <td>{{ $barang->ruang->nama }}</td>
                                                <td>{{ $barang->rusak ?? "0" }} Pcs</td>
                                                <td>{{ $barang->hilang ?? "0" }} Pcs</td>
                                                <td class="text-center">
                                                    <a href="{{ url('kalab/barang/' . $barang->id) }}"
                                                        class="btn btn-info">
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
                                <div class="pagination px-4 py-2 float-right">
                                    {{ $barangs->appends(Request::all())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
