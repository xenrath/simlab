@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Peminjaman</h1>
      <div class="section-header-button">
        <a href="{{ url('peminjam/normal/peminjaman-new/create') }}" class="btn btn-primary">Buat Peminjaman</a>
      </div>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Data Peminjaman</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Waktu</th>
                  <th>Praktik</th>
                  <th class="text-center">Opsi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pinjams as $pinjam)
                  <tr>
                    <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                    @php
                      $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                      $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                    @endphp
                    <td class="align-top py-3">
                      @if ($pinjam->praktik_id == '3')
                        {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                      @else
                        @if ($tanggal_awal == $tanggal_akhir)
                          {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_awal }}
                        @else
                          {{ $pinjam->jam_awal }}, {{ $tanggal_awal }} <br> {{ $pinjam->jam_akhir }},
                          {{ $tanggal_akhir }}
                        @endif
                      @endif
                    </td>
                    <td class="align-top py-3 text-wrap">
                      @if ($pinjam->praktik_id != null)
                        @if ($pinjam->praktik_id == '1')
                          {{ $pinjam->praktik->nama }} <br>
                          ({{ $pinjam->ruang->nama }})
                        @else
                          {{ $pinjam->praktik->nama }}
                        @endif
                      @else
                        -
                      @endif
                    </td>
                    <td class="text-center align-top py-3">
                      <form action="{{ url('peminjam/normal/peminjaman-new/' . $pinjam->id) }}" method="POST"
                        id="hapus-{{ $pinjam->id }}">
                        @csrf
                        @method('delete')
                        <a href="{{ url('peminjam/normal/peminjaman-new/' . $pinjam->id) }}" class="btn btn-info mr-1">
                          Detail
                        </a>
                        @if ($pinjam->peminjam_id == auth()->user()->id)
                          <button type="button" class="btn btn-danger"
                            data-confirm="Batalkan Peminjaman?|Apakah anda yakin akan membatalkan peminjaman ini?"
                            data-confirm-yes="modalHapus({{ $pinjam->id }})">
                            Hapus
                          </button>
                        @endif
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
  </section>
  <script>
    function modalHapus(id) {
      $("#hapus-" + id).submit();
    }
  </script>
@endsection
