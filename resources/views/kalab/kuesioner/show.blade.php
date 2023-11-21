@extends('layouts.app')

@section('title', 'Detail Kuesioner')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('kalab/kuesioner') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Kuesioner</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Kuesioner</h4>
                    <div class="card-header-action">
                        <a href="{{ url('kalab/kuesioner/download/' . $kuesioner->id . '/' . date('Y', strtotime($kuesioner->created_at))) }}"
                            class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-download mr-1"></i>Unduh
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                <strong>Judul Kuesioner</strong><br>
                                {{ $kuesioner->judul }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        <strong>Tahun</strong><br>
                                        {{ date('Y', strtotime($kuesioner->created_at)) }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        <strong>Jumlah Responden</strong><br>
                                        {{ count($data) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Mahasiswa</h4>
                    <div class="card-header-action">
                        <a href="{{ url('kalab/kuesioner/grafik/' . $kuesioner->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-chart-pie mr-1"></i>Grafik
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered table-md">
                        <thead>
                            <tr>
                                <th class="align-middle text-center" style="width: 40px">No</th>
                                <th class="align-middle">Nama Mahasiswa</th>
                                <th class="align-middle">Prodi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $d['nama'] }}</td>
                                    <td>{{ $d['prodi'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
