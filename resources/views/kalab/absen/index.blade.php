@extends('layouts.app')

@section('title', 'Data Kunjungan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Kunjungan</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-statistic-1 rounded-0 mb-3">
                        <div class="card-icon bg-primary rounded-0">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Jumlah Kunjugan Hari Ini</h4>
                            </div>
                            <div class="card-body">
                                {{ $jumlah }} orang
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Kunjungan</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No.</th>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Keterangan Asal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absens as $key => $absen)
                                    <tr>
                                        <td class="text-center">{{ $absens->firstItem() + $key }}</td>
                                        <td>{{ date('d M Y', strtotime($absen->created_at)) }}</td>
                                        @if ($absen->user_id)
                                            <td>{{ $absen->user->nama }}</td>
                                            <td>
                                                Mahasiswa -
                                                {{ $absen->user->subprodi->jenjang }}
                                                {{ $absen->user->subprodi->nama }}
                                            </td>
                                        @else
                                            @if ($absen->username == null && $absen->institusi == null)
                                                <td>
                                                    {{ $absen->nama }}
                                                    <br>
                                                    <small>({{ $absen->nim }})</small>
                                                </td>
                                                <td>
                                                    {{ $absen->prodi }}
                                                    <br>
                                                    <small>(Mahasiswa)</small>
                                                </td>
                                            @else
                                                <td>{{ $absen->username }}</td>
                                                <td>{{ $absen->institusi }}</td>
                                            @endif
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if ($absens->total() > 10)
                            <div class="pagination px-4 py-2 d-flex justify-content-md-end">
                                {{ $absens->appends(Request::all())->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
