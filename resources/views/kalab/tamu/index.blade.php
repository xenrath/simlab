@extends('layouts.app')

@section('title', 'Data Tamu')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Pengguna</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Tamu</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Nama</th>
                                            <th>Institusi</th>
                                            <th style="width: 20px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tamus as $key => $tamu)
                                            <tr>
                                                <td class="text-center">{{ $tamus->firstItem() + $key }}</td>
                                                <td class="text-wrap">
                                                    {{ $tamu->nama }}
                                                </td>
                                                <td class="text-wrap">{{ $tamu->institusi }}</td>
                                                <td>
                                                    <a href="{{ url('kalab/peminjam/' . $tamu->id) }}"
                                                        class="btn btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
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
                        <div class="card-footer">
                            <div class="pagination float-right">
                                {{ $tamus->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
