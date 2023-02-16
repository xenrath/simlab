@extends('layouts.app')

@section('title', 'Rusak')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Rusak</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Barang Rusak</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Tanggal Pinjam</th>
                <th>Nama Peminjam</th>
                <th class="text-center">Opsi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pinjams as $key => $pinjam)
              <tr>
                <td class="text-center align-middle">{{ $pinjams->firstItem() + $key }}</td>
                <td>{{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}</td>
                <td>{{ $pinjam->peminjam->nama }}</td>
                <td class="text-center">
                  <a href="{{ url('laboran/rusak/' . $pinjam->id) }}" class="btn btn-primary mr-2">
                    <i class="fas fa-clipboard-check"></i>
                    <span class="d-none d-md-inline">&nbsp;Konfirmasi</span>
                  </a>
                  @php
                  $jam = date('G');
                  if ($jam >= '0' && $jam <= '11' ) { $waktu='Pagi' ; } else if ($jam>= '12' && $jam <= '14' ) {
                      $waktu='Siang' ; } else if ($jam>= '15' && $jam <= '18' ) { $waktu='Sore' ; } else {
                        $waktu='malam' ; } @endphp <a
                        href="https://web.whatsapp.com/send?phone=62{{ $pinjam->peminjam->telp }}&text=Selamat%20{{ $waktu }}%20{{ $pinjam->peminjam->nama }}%0ASekedar%20mengingatkan,%20masa%20peminjaman%20barang%20Anda%20telah%20habis.%20Mohon%20untuk%20segera%20dikembalikan%0ATerimakasih"
                        target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i>
                        </a>
                </td>
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