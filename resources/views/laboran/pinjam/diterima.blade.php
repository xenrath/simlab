@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Data Peminjaman</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Peminjaman ({{ ucfirst($status) }})</h4>
      </div>
      <div class="card-body">
        <table class="table table-hover" id="table-1">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th>Nama Peminjam</th>
              <th>Tanggal Pinjam</th>
              <th>Tanggal Kembali</th>
              <th class="text-center">Barang</th>
              <th class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pinjams as $pinjam)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $pinjam->peminjam->nama }}</td>
              <td>{{ $pinjam->jam_awal }}, {{ date('d-m-Y', strtotime($pinjam->tanggal_awal)) }}</td>
              <td>{{ $pinjam->jam_akhir }}, {{ date('d-m-Y', strtotime($pinjam->tanggal_akhir)) }}</td>
              @if ($pinjam->status == 'menunggu' )
              <td class="text-center">
                <a href="{{ url('laboran/pinjam/menunggu/' . $pinjam->id) }}" class="btn btn-sm btn-info">
                  <i class="fas fa-eye"></i>
                  <span class="d-none d-md-inline">&nbsp;Detail</span>
                </a>
                <a href="{{ url('laboran/pinjam/terima/' . $pinjam->id) }}" class="btn btn-sm btn-primary mx-1">
                  <i class="fas fa-check"></i>
                  <span class="d-none d-md-inline">&nbsp;Terima</span>
                </a>
                <a href="" class="btn btn-sm btn-danger">
                  <i class="fas fa-times"></i>
                  <span class="d-none d-md-inline">&nbsp;Tolak</span>
                </a>
              </td>
              @endif
              @if ($pinjam->status == "diterima")
              <td class="text-center">
                <a href="{{ url('laboran/pinjam/diterima/' . $pinjam->id) }}" class="btn btn-sm btn-info">
                  <i class="fas fa-eye"></i>
                  <span class="d-none d-md-inline">&nbsp;Detail</span>
                </a>
              </td>
              <td class="text-center">
                @php
                $now = strtotime(Carbon\Carbon::now()->format('Y-m-d'));
                $tanggal_akhir = strtotime($pinjam->tanggal_akhir);
                $diff = abs(round(($now - $tanggal_akhir) / 86400));
                @endphp
                @if ($now > $tanggal_akhir)
                <span class="badge badge-danger">Expired</span>
                @else
                <span class="badge badge-info">
                  @if ($diff == 0)
                  Hari ini
                  @else
                  {{ $diff }} Hari Lagi
                  @endif
                </span>
                @endif
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection