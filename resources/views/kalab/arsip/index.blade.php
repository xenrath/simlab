@extends('layouts.app')

@section('title', 'Data Arsip')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Arsip</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card rounded-0">
                        <div class="card-header">
                            <h4>Data Arsip</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th>Tahun</th>
                                            <th class="text-center" style="width: 40px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tahuns as $tahun)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $tahun->nama }}</td>
                                                <td class="text-center">
                                                    <a href="{{ url('kalab/arsip/' . $tahun->nama) }}"
                                                        class="btn btn-info rounded-0">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="4">- Data tidak ditemukan -</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
