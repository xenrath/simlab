@extends('layouts.app')

@section('title', 'Data Kuesioner')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Kuesioner</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Kuesioner</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 40px">No</th>
                                    <th>Judul</th>
                                    <th>Tahun</th>
                                    <th class="text-center" style="width: 120px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $key => $d)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $d['judul'] }}</td>
                                        <td>{{ $d['tahun'] }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('kalab/kuesioner/' . $d['kuesioner_id'] . '/' . $d['tahun']) }}"
                                                class="btn btn-info rounded-0">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ url('kalab/kuesioner/download/' . $d['kuesioner_id'] . '/' . $d['tahun']) }}"
                                                class="btn btn-primary rounded-0" target="_blank">
                                                <i class="fas fa-download"></i>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
