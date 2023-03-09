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
                  <th>Peminjam</th>
                  <th>Waktu</th>
                  <th>Praktik</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($pinjams as $key => $pinjam)
                  <tr>
                    <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                    <td class="align-top py-3">{{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}</td>
                    <td class="align-top py-3">{{ $pinjam->peminjam->nama }}</td>
                    <td class="align-top py-3">
                      @if ($pinjam->praktik_id != null)
                        @if ($pinjam->praktik_id == '1')
                          {{ $pinjam->praktik->nama }} <br>
                          ({{ $pinjam->ruang->nama }})
                        @else
                          {{ $pinjam->praktik->nama }} <br>
                          ({{ $pinjam->keterangan }})
                        @endif
                      @else
                        -
                      @endif
                    </td>
                    <td class="align-top py-3">
                      <a href="{{ url('laboran/rusak/' . $pinjam->id) }}" class="btn btn-primary mr-2">
                        Konfirmasi
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
