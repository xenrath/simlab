@extends('layouts.app')

@section('title', 'Data Stok')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Master Data Stok</h1>
    <div class="section-header-button">
      <a href="{{ url('admin/stokbarang/create') }}" class="btn btn-primary">Tambah</a>
    </div>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Data Stok</h4>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Normal</th>
                    <th>Jumlah Rusak</th>
                    <th>Tanggal Masuk</th>
                    <th class="text-center">Opsi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($stoks as $stok)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-wrap">{{ $stok->barang->nama }}</td>
                    <td class="text-wrap">{{ $stok->normal }} {{ $stok->satuan->nama }}</td>
                    <td class="text-wrap">{{ $stok->rusak }} {{ $stok->satuan->nama }}</td>
                    <td class="text-wrap">{{ date('d M Y', strtotime($stok->created_at)) }}</td>
                    <td class="text-center w-25">
                      <form action="{{ url('admin/stokbarang/' . $stok->id) }}" method="POST" id="del-{{ $stok->id }}">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger"
                          data-confirm="Hapus Data?|Yakin hapus data stok barang <b>{{ $stok->barang->nama }}</b>?"
                          data-confirm-yes="modalDelete({{ $stok->id }})">
                          <i class="fas fa-trash" aria-hidden="true"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td class="text-center" colspan="5">- Data tidak ditemukan -</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="pagination">
              {{ $stoks->appends(Request::all())->links('pagination::bootstrap-4') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function modalDelete(id) {
    $("#del-" + id).submit();
  }
</script>
@endsection