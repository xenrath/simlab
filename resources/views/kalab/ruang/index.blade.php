@extends('layouts.app')

@section('title', 'Data Ruang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Ruang</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Ruang</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Ruang Lab</th>
                                            <th>Laboran</th>
                                            <th class="text-center" style="width: 40px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ruangs as $key => $ruang)
                                            <tr>
                                                <td class="text-center">{{ $ruangs->firstItem() + $key }}</td>
                                                <td>{{ $ruang->nama }}</td>
                                                <td>{{ $ruang->laboran->nama ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ url('kalab/ruang/' . $ruang->id) }}" class="btn btn-info">
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
                                @if ($ruangs->total() > 10)
                                    <div class="pagination px-4 py-2 d-flex justify-content-md-end">
                                        {{ $ruangs->appends(Request::all())->links('pagination::bootstrap-4') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
