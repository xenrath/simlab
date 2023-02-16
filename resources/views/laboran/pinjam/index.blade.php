@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Peminjaman</h1>
    <div class="section-header-button">
      <a href="{{ url('laboran/pinjam/create') }}" class="btn btn-primary">Tambah Peminjaman</a>
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
            <div class="p-4">
              <form action="{{ url('laboran/peminjaman') }}" method="GET" id="get-filter">
                <div class="float-xs-right float-sm-right float-left mb-3">
                  <div class="input-group">
                    <input type="search" class="form-control" name="keyword" placeholder="Cari"
                      value="{{ Request::get('keyword') }}" autocomplete="off" onsubmit="event.preventDefault();
                    document.getElementById('get-filter').submit();">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Waktu Pinjam</th>
                    <th>Ruang / Lab.</th>
                    <th>Status</th>
                    <th class="text-center" width="240">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($pinjams as $key => $pinjam)
                  <tr>
                    <td class="text-center ">{{ $pinjams->firstItem() + $key }}</td>
                    @php
                    $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                    $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                    @endphp
                    <td>
                      @if ($tanggal_awal == $tanggal_akhir)
                      {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_awal }}
                      @else
                      {{ $pinjam->jam_awal }}, {{ $tanggal_awal }} <br> {{ $pinjam->jam_akhir }}, {{ $tanggal_akhir }}
                      @endif
                    </td>
                    <td class="">
                      @if ($pinjam->ruang_id)
                      {{ $pinjam->ruang->nama }}
                      @else
                      -
                      @endif
                    </td>
                    <td class="">
                      @php
                      $now = \Carbon\Carbon::now();
                      $pinjam
                      @endphp
                      @if ($pinjam->status == 'menunggu')
                      <div class="badge badge-primary">Aktif</div>
                      @else
                      <div class="badge badge-info">Draft</div>
                      @endif
                    </td>
                    <td class="text-center">
                      <form action="{{ url('laboran/pinjam/' . $pinjam->id) }}" method="POST" id="del-{{ $pinjam->id }}">
                        @csrf
                        @method('delete')
                        <a href="{{ url('laboran/pinjam/' . $pinjam->id . '/edit') }}" class="btn btn-warning">
                          <i class="fas fa-pen"></i>
                        </a>
                        @if ($pinjam->status == "draft")
                        <button type="submit" class="btn btn-danger"
                          data-confirm="Hapus Data|Yakin menghapus peminjaman?"
                          data-confirm-yes="modalDelete({{ $pinjam->id }})">
                          <i class="fas fa-trash" aria-hidden="true"></i>
                        </button>
                        @else
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                          data-target="#modalKonfirmasi{{ $pinjam->id }}">
                          <i class="fas fa-check"></i>
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
          <div class="card-footer">
            <div class="pagination float-right">
              {{ $pinjams->appends(Request::all())->links('pagination::bootstrap-4') }}
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
        <a href="{{ url('laboran/pinjam/' . $pinjam->id . '/konfirmasi') }}" class="btn btn-primary">Konfirmasi</a>
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