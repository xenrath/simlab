@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('peminjam/pinjam/' . lcfirst($prodi->nama)) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Pinjam Barang</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Buat Peminjaman (Lab. {{ $prodi->nama }})</h4>
            <div class="card-header-action">
              <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalBarang">Pilih Barang</a>
            </div>
          </div>
          <form action="{{ url('peminjam/pinjam') }}" method="POST">
            @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tanggal_awal">Tanggal Pinjam</label>
                    <input type="date" class="form-control @error('tanggal_awal') is-invalid @enderror"
                      name="tanggal_awal" id="tanggal_awal" min="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                      value="{{ old('tanggal_awal') }}">
                    @error('tanggal_awal')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tanggal_akhir">Tanggal Kembali</label>
                    <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror"
                      name="tanggal_akhir" id="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                    @error('tanggal_akhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="jam_awal">Jam Pinjam</label>
                    <input type="time" class="form-control @error('jam_awal') is-invalid @enderror" name="jam_awal"
                      id="jam_awal" value="{{ old('jam_awal') }}">
                    @error('jam_awal')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="jam_akhir">Jam Kembali</label>
                    <input type="time" class="form-control @error('jam_akhir') is-invalid @enderror" name="jam_akhir"
                      id="jam_akhir" value="{{ old('jam_akhir') }}">
                    @error('jam_akhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="ruang_id">Ruang Lab.</label>
                    <select class="form-control @error('ruang_id') is-invalid @enderror" id="ruang_id" name="ruang_id">
                      <option value="">- Pilih Ruang -</option>
                      @foreach ($ruangs as $ruang)
                      <option value="{{ $ruang->id }}" {{ old('ruang_id')==$ruang->id ? 'selected' : '' }}>{{
                        $ruang->nama }}</option>
                      @endforeach
                    </select>
                    @error('ruang_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="keperluan">Keperluan</label>
                    <textarea class="form-control @error('keperluan') is-invalid @enderror" id="keperluan"
                      name="keperluan">{{ old('keperluan') }}</textarea>
                    @error('keperluan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered mt-2" width="100%">
                  <thead>
                    <tr>
                      <th class="text-center">No.</th>
                      <th>Nama Barang</th>
                      <th class="text-center">Stok Barang</th>
                      <th>Jumlah Pinjam</th>
                    </tr>
                  </thead>
                  <tbody id="dataItems">
                    <tr>
                      <td colspan="4" class="text-center">Belum ada barang yang dipilih</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-primary float-end">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarang" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="m-0 font-weight-bold">Pilih Pinjaman Barang</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-6">
            <p class="mt-2 text-wrap">Jumlah : <strong id="countChecked">0</strong></p>
          </div>
          <div class="col-6 text-right">
            <button type="button" class="btn btn-warning btn-sm mt-1 text-white" id="uncheckAll">Uncheck
              Semua</button>
            <button type="button" class="btn btn-primary btn-sm mt-1 text-white ml-1" id="addItem">Masukan
              Barang</button>
          </div>
        </div>
        <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
          <li class="nav-item border rounded mr-1">
            <a class="nav-link active" id="barang-tab" data-toggle="tab" href="#barang" role="tab"
              aria-controls="barang" aria-selected="true">
              <span class="font-weight-bold">Barang</span>
            </a>
          </li>
          <li class="nav-item border rounded ml-1">
            <a class="nav-link" id="bahan-tab" data-toggle="tab" href="#bahan" role="tab" aria-controls="bahan"
              aria-selected="false">
              <span class="font-weight-bold">Bahan habis pakai</span>
            </a>
          </li>
        </ul>
        <div class="tab-content w-100 mt-3" id="myTabContent">
          <div class="tab-pane fade show active" id="barang" role="tabpanel" aria-labelledby="barang-tab">
            <div class="table-responsive">
              <table class="table table-hover" id="table-1">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Jumlah Stok</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($barangs as $barang)
                  <tr>
                    <td class="text-center pb-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkboxId" value="{{ $barang->id }}">
                      </div>
                    </td>
                    <td>{{ $barang->kode }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td class="text-center">{{ $barang->stok }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="bahan" role="tabpanel" aria-labelledby="bahan-tab">
            <div class="table-responsive">
              <table class="table table-hover" id="table-1">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th>Kode Bahan</th>
                    <th>Nama Bahan</th>
                    <th class="text-center">Jumlah Stok</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($bahans as $bahan)
                  <tr>
                    <td class="text-center pb-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkboxId" value="{{ $bahan->id }}">
                      </div>
                    </td>
                    <td>{{ $bahan->kode }}</td>
                    <td>{{ $bahan->nama }}</td>
                    <td class="text-center">{{ $bahan->stok }}</td>
                  </tr>
                  @empty
                  <td class="text-center" colspan="4">- Data bahan habis pakai kosong -</td>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var tanggalAwal = document.getElementById('tanggal_awal');
  var tanggalAkhir = document.getElementById('tanggal_akhir');
  tanggalAwal.addEventListener('change', function() {
    tanggalAkhir.value = "";
    tanggalAkhir.setAttribute('min', this.value);
  });
  
  var checkboxes = document.querySelectorAll('#checkboxId');
  var count = 0;
  var listItem = [];
  var addItem = document.getElementById('addItem');
  for (var checkbox of checkboxes) {
    checkbox.addEventListener('click', function () {
      if (this.checked == true) {
        count++;
        listItem.push(this.value);
        
        addItem.setAttribute("data-toggle", "modal");
        addItem.setAttribute("data-target", "#modalBarang");
      } else {
        count--;
        listItem = listItem.filter(e => e !== this.value);
        if (count === 0) {
          addItem.removeAttribute("data-toggle");
          addItem.removeAttribute("data-target");
        }
      }
      document.getElementById("countChecked").textContent = listItem.length;
    });
  };
  var dataItems = document.getElementById('dataItems');
  addItem.addEventListener('click', function () {
    if (listItem.length === 0) {
      alert("Pilih barang terlebih dahulu!");
    } else {
      $item = listItem;
      $.ajax({
        url: "{{ url('peminjam/barang-dipilih') }}",
        type: "GET",
        data: { "items": $item },
        success: function(data) {
          // $('#tableItems').show()
          $('#dataItems').html(data);
          console.log(data);
        },
      });
    }
  });
  var uncheckAll = document.getElementById('uncheckAll')
  uncheckAll.addEventListener('click', function () {
    $('input[type="checkbox"]:checked').prop('checked', false);
    listItem = [];
    document.getElementById("countChecked").textContent = listItem.length;
    $item = listItem;
    $.ajax({
      url: "{{ url('peminjam/barang-dipilih') }}",
      type: "GET",
      data: { "items": $item },
      success: function(data) {
        // $('#tableItems').show()
        $('#dataItems').html(data);
      },
    });
  });
  function modalDelete(id) {
    $("#del-" + id).submit();
  };
</script>
@endsection