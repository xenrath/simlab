@extends('layouts.app')

@section('title', 'Pengambilan Bahan')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Pengambilan Bahan</h1>
    @if ($isadmin)
    <div class="section-header-button">
      <a href="{{ url('laboran/bahan/create') }}" class="btn btn-primary">Tambah Pengambilan</a>
    </div>
    @endif
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
            <h4>Data Pengambilan</h4>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Ruang / Lab.</th>
                    <th>Tanggal</th>
                    <th class="text-center" width="240">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($pengambilans as $pengambilan)
                  <tr>
                    <td class="text-center ">{{ $loop->iteration }}</td>
                    <td>{{ $pengambilan->ruang->nama }}</td>
                    <td>{{ date('d M Y', strtotime($pengambilan->created_at)) }}</td>
                    <td class="text-center">
                      <a href="{{ url('laboran/bahan/' . $pengambilan->id) }}" class="btn btn-info">
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@foreach ($pengambilans as $pengambilan)
<div class="modal fade" id="modalKonfirmasi{{ $pengambilan->id }}" role="dialog" aria-labelledby="modalKonfirmasi"
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
        <a href="{{ url('laboran/bahan/' . $pengambilan->id . '/konfirmasi') }}" class="btn btn-primary">Konfirmasi</a>
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