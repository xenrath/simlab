@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Peminjaman</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Data Peminjaman</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <tr>
                <th class="text-center">No.</th>
                <th>Peminjam</th>
                <th>Waktu Pinjam</th>
                <th>Ruang Lab</th>
                <th>Opsi</th>
              </tr>
              @forelse($pinjams as $pinjam)
                <tr>
                  <td class="text-center align-top py-2">{{ $loop->iteration }}</td>
                  @if ($pinjam->kelompoks()->first()->anggota)
                    <td class="align-middle py-2">
                      <span class="bullet"></span>&nbsp;{{ $pinjam->kelompoks->first()->m_ketua->nama }} (Ketua) <br>
                      @php
                        $kelompok = $pinjam->kelompoks->first();
                      @endphp
                      @foreach ($kelompok->anggota as $anggota)
                        <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                        <br>
                      @endforeach
                    </td>
                  @else
                    <td class="align-middle">
                      {{ $pinjam->peminjam->nama }}
                    </td>
                  @endif
                  @php
                    $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                    $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                  @endphp
                  <td class="align-top py-2">
                    @if ($tanggal_awal == $tanggal_akhir)
                      {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_awal }}
                    @else
                      {{ $pinjam->jam_awal }}, {{ $tanggal_awal }} <br> {{ $pinjam->jam_akhir }}, {{ $tanggal_akhir }}
                    @endif
                  </td>
                  <td class="align-top py-2">
                    {{ $pinjam->ruang->nama }}
                  </td>
                  <td class="align-top py-2">
                    <a href="{{ url('laboran/peminjaman/' . $pinjam->id) }}" class="btn btn-info mr-1">
                      Lihat
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                </tr>
              @endforelse
            </table>
          </div>
        </div>
        <div class="card-footer">
          <div class="pagination">
            {{ $pinjams->appends(Request::all())->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
