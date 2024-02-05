@extends('layouts.app')

@section('title', 'Barang Hilang')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('kalab/barang') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Barang Hilang</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Barang Hilang</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th>Nama Barang</th>
                                            <th>Ruang Lab</th>
                                            <th>Jumlah Hilang</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($detail_pinjams as $key => $detail_pinjam)
                                            <tr>
                                                <td class="text-center">{{ $detail_pinjams->firstItem() + $key }}</td>
                                                <td>{{ $detail_pinjam->barang->nama }}</td>
                                                <td>{{ $detail_pinjam->barang->ruang->nama }}</td>
                                                <td>{{ $detail_pinjam->hilang }} Pcs</td>
                                                <td>{{ date('d M Y', strtotime($detail_pinjam->created_at)) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($detail_pinjams->total() > 10)
                            <div class="card-footer">
                                <div class="pagination float-right">
                                    {{ $detail_pinjams->appends(Request::all())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
