@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Pengembalian</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data Pengembalian</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Tanggal Pinjam</th>
                <th>Nama Barang</th>
                <th>Jumlah Rusak</th>
                <th>Ruang Lab</th>
                <!-- <th class="text-center">Opsi</th> -->
              </tr>
            </thead>
            <tbody>
              @forelse($detailpinjams as $key => $detailpinjam)
              <tr>
                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                <td>{{ date('d M Y', strtotime($detailpinjam->pinjam->tanggal_awal)) }}</td>
                <td>{{ $detailpinjam->barang->nama }}</td>
                <td>{{ $detailpinjam->rusak }} {{ ucfirst($detailpinjam->satuan->nama) }}</td>
                <td>{{ $detailpinjam->pinjam->ruang->nama }}</td>
                <!-- <td class="text-center">
                  <a href="{{ url('peminjam/tagihan/' . $detailpinjam->id) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i>
                    <span class="d-none d-md-inline">&nbsp;Lihat</span>
                  </a>
                </td> -->
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">- Data tidak ditemukan -</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function modalBatal(id) {
    $("#batal-" + id).submit();
  }
</script>
@endsection