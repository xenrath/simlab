@extends('layouts.app')

@section('title', 'Barang Rusak')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('kalab/barang') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Barang Rusak</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Barang Rusak</h4>
                    {{-- <div class="card-header-action">
                        <a href="{{ url('kalab/barang/rusak/unduh') }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-download"></i>
                            Unduh
                        </a>
                    </div> --}}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Nama Barang</th>
                                    <th>Ruang Lab</th>
                                    <th>Jumlah Rusak</th>
                                    {{-- <th>Tanggal</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangs as $key => $barang)
                                    <tr>
                                        <td class="text-center">{{ $barangs->firstItem() + $key }}</td>
                                        <td>{{ $barang->nama }}</td>
                                        <td>{{ $barang->ruang->nama }}</td>
                                        <td>{{ $barang->rusak }} Pcs</td>
                                        {{-- <td>{{ date('d M Y', strtotime($barang->updated)) }}</td> --}}
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
