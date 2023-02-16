@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('laboran/pengembalian') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Detail Pinjaman</h1>
  </div>
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Detail Data Pinjaman</h4>
        <div class="card-header-action">
          @if ($pinjam->status == 'menunggu')
          <span class="badge badge-warning">Menunggu</span>
          @elseif ($pinjam->status == 'disetujui')
          <span class="badge badge-primary">Disetujui</span>
          @elseif ($pinjam->status == 'selesai')
          <span class="badge badge-success">Selesai</span>
          @endif
        </div>
      </div>
      <div class="card-body p-0">
        <div class="row">
          <div class="col-md-6">
            <table class="table">
              <tr>
                <th class="w-25">Waktu Pinjam</th>
                <td class="w-50">{{ $pinjam->jam_awal }}, {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}</td>
              </tr>
              <tr>
                <th class="w-25">Waktu Kembali</th>
                <td class="w-50">{{ $pinjam->jam_akhir }}, {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}</td>
              </tr>
              <tr>
                <th class="w-25">Keterangan</th>
                <td class="w-50">
                  @if ($pinjam->keterangan)
                  {{ $pinjam->keterangan }}</td>
                @else
                -
                @endif
              </tr>
            </table>
          </div>
          <div class="col-md-6 p-0">
            <table class="table">
              <tr>
                <th class="w-25">Ruang Lab.</th>
                <td class="w-50">{{ $pinjam->ruang->nama }}</td>
              </tr>
              <tr>
                <th class="w-25">Laboran</th>
                <td class="w-50">
                  @if ($pinjam->laboran_id)
                  {{ $pinjam->laboran->nama }}<br>
                  @endif
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th>Nama Barang</th>
                <th class="tetx-center">Kategori</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($detailpinjams as $detailpinjam)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $detailpinjam->barang->nama }}</td>
                <td class="tetx-center">{{ ucfirst($detailpinjam->barang->kategori) }}</td>
                <td>{{ $detailpinjam->jumlah }} {{ ucfirst($detailpinjam->satuan->nama) }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-header">
        <h4>Tambah Pinjaman</h4>
      </div>
      <form action="{{ url('laboran/pengembalian/update/' . $pinjam->id) }}" method="POST" id="form-submit">
        @csrf
        <div class="card-body p-0">
          <div class="p-4">
            <a href="" class="btn btn-info float-right mb-3" data-toggle="modal" data-target="#modalBarang">Pilih
              Barang</a>
          </div>
          <div class="table-responsive-sm">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama Barang</th>
                  <th>Stok Barang</th>
                  <th class="text-center">Jumlah Pinjam</th>
                </tr>
              </thead>
              <tbody id="dataItems">
                <tr>
                  <td colspan="5" class="text-center">Belum ada barang yang dipilih</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="button" class="btn btn-primary" onclick="checkData()">
            <i class="fas fa-save"></i>&nbsp;Update
          </button>
        </div>
      </form>
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
              <span class="font-weight-bold">Bahan</span>
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
                    <td class="text-center">{{ $barang->stok }} {{ $barang->satuan->singkatan }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="bahan" role="tabpanel" aria-labelledby="bahan-tab">
            <div class="table-responsive">
              <table class="table table-hover" id="table-2">
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
                    <td class="text-center">{{ $bahan->stok }} {{ $bahan->satuan->singkatan }}</td>
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
      console.log(listItem);
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
        url: "{{ url('laboran/pilih') }}",
        type: "GET",
        data: { "items": $item },
        dataType: "json",
        success: function(data) {
          if (data != null) {
            $("#dataItems").empty();
            $no = 1;
            $.each(data, function (key, value) {
              $("#dataItems").append("<tr>\
                <td class='text-center'>" + $no++ + "</td>\
                <td>" + value.nama + " (" + value.kategori + ")</td>\
                <td class='text-center'>" + value.stok + " " + value.satuan.singkatan + "</td>\
                <td>\
                  <div class='input-group'>\
                    <input class='form-control' type='number' id='jumlahId' name='jumlah[" + key + "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 ? Math.abs(this.value) : null' required>\
                    <input type='hidden' name='barang_id[" + key + "]' value='" + value.id + "' class='form-control'>\
                    <select class='custom-select' id='satuan" + key + "' name='satuan[" + key + "]'>\
                    </select>\
                  </div>\
                </td>\
              </tr>");
              if (value.kategori == "bahan") {
                if (value.satuan.kategori == "volume") {
                  $("#satuan" + key + "").append("<option value='1'>l</option>\
                  <option value='2'>ml</option>");
                  $("#satuan" + key + "").val(value.satuan_id).attr('selected', true);
                } else {
                  $("#satuan" + key + "").append("<option value='3'>kg</option>\
                  <option value='4'>g</option>\
                  <option value='5'>mg</option>");
                  $("#satuan" + key + "").val(value.satuan_id).attr('selected', true);
                }
              } else {
                $("#satuan" + key + "").append("<option value='6'>pcs</option>");
              }
            });
            console.log(data);
          }
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
      url: "{{ url('laboran/pilih') }}",
      type: "GET",
      data: { "items": $item },
      dataType: "json",
      success: function(data) {
        if (data == null) {
          $("#dataItems").empty();
          $("#dataItems").append("<tr>\
            <td colspan='4' class='text-center'>- Belum ada barang yang dipilih -</td>\
          </tr>");
        }
      },
    });
  });
  function checkData() {
    if (count === 0) {
      swal("Error", "Pilih barang terlebih dahulu!", "error");
    } else {
      $('#form-submit').submit();
    }
  }
</script>
@endsection