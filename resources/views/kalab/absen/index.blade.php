@extends('layouts.app')

@section('title', 'Absen')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Data Kunjungan</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-md-4">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <a href="">
              <div class="card-header">
                <h4>Jumlah Kunjugan Hari Ini</h4>
              </div>
            </a>
            <div class="card-body">
              {{ count($jumlah) }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4>Data Kunjungan</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Prodi</th>
                <th>Institusi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($absens as $key => $absen)
              <tr>
                <td class="text-center">{{ $absens->firstItem() + $key }}</td>
                <td>{{ date('d M Y', strtotime($absen->created_at)) }}</td>
                @if ($absen->user_id)
                <td>{{ $absen->user->nama }}</td>
                <td>Mahasiswa</td>
                <td>{{ $absen->user->subprodi->jenjang }} {{ $absen->user->subprodi->nama }}</td>
                <td>-</td>
                @else
                <td>{{ $absen->username }}</td>
                <td>Tamu</td>
                <td>-</td>
                <td>{{ $absen->institusi }}</td>
                @endif
              </tr>
              @empty
              <tr>
                <td class="text-center" colspan="6">- Data tidak ditemukan -</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          <div class="pagination p-4">
            {{ $absens->appends(Request::all())->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection