@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Peminjaman</h1>
      <div class="section-header-button">
        <a href="{{ url('peminjam/estafet/peminjaman/create') }}" class="btn btn-primary">Buat Peminjaman</a>
      </div>
    </div>
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          <div class="alert-title">GAGAL !</div>
        </div>
        <p>{{ session('error') }}</p>
      </div>
    @endif
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Peminjaman</h4>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Waktu Praktik</th>
                      <th>Ruang Lab</th>
                      <th>Kelompok</th>
                      <th>Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($pinjams as $key => $pinjam)
                      <tr>
                        <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                        <td class="align-top py-3">
                          {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                        </td>
                        <td class="align-top py-3">{{ $pinjam->ruang->nama }}</td>
                        <td class="align-top py-3">
                          @foreach ($pinjam->kelompoks as $kelompok)
                            <div class="border rounded p-2 mb-2">
                              {{ $kelompok->nama }} - ({{ $kelompok->shift }}, {{ $kelompok->jam }})
                              <br>
                              <span class="bullet"></span>&nbsp;{{ $kelompok->m_ketua->nama }} (Ketua)<br>
                              @foreach ($kelompok->anggota as $anggota)
                                <span
                                  class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                                <br>
                              @endforeach
                            </div>
                          @endforeach
                        </td>
                        <td class="align-top py-3">
                          <form action="{{ url('peminjam/estafet/peminjaman/' . $pinjam->id) }}" method="POST"
                            id="del-{{ $pinjam->id }}">
                            @csrf
                            @method('delete')
                            @if ($pinjam->peminjam_id == auth()->user()->id)
                              <a href="{{ url('peminjam/estafet/peminjaman/' . $pinjam->id) }}" class="btn btn-info">
                                Detail
                              </a>
                            @endif
                            <button type="submit" class="btn btn-danger"
                              data-confirm="Hapus Data|Yakin menghapus peminjaman?"
                              data-confirm-yes="modalDelete({{ $pinjam->id }})">
                              Hapus
                            </button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
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
  @foreach ($pinjams as $pinjam)
    <div class="modal fade" id="modalKonfirmasi{{ $pinjam->id }}" role="dialog" aria-labelledby="modalKonfirmasi"
      aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="font-weight-bold">Konfirmasi Peminjaman</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Yakin konfirmasi peminjaman ini?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
              Batal
            </button>
            <a href="{{ url('peminjam/estafet/peminjaman/' . $pinjam->id . '/konfirmasi') }}"
              class="btn btn-primary">Konfirmasi</a>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <script>
    function modalDelete(id) {
      $("#del-" + id).submit();
    }
  </script>
@endsection
