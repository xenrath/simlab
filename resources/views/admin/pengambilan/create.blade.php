@extends('layouts.app')

@section('title', 'Tambah Pengambilan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/pengambilan') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Tambah Pengambilan</h1>
  </div>
  @if (session('status'))
  <div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
      <div class="alert-title">GAGAL !</div>
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <p>
        @foreach (session('status') as $error)
        <span class="bullet"></span>&nbsp;{{ strtoupper($error) }}
        <br>
        @endforeach
      </p>
    </div>
  </div>
  @endif
  <div class="section-body">
    <form action="{{ url('admin/pengambilan') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
      <div class="card">
        <div class="card-header">
          <h4>Buat Pengambilan</h4>
        </div>
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="ruang_id">Ruang / Lab *</label>
                <select name="ruang_id" id="ruang_id" class="form-control selectric" onchange="getlaboran()">
                  <option value="">- Pilih Ruang -</option>
                  @foreach ($ruangs as $ruang)
                  <option value="{{ $ruang->id }}" {{ old('ruang_id')==$ruang->id ? 'selected' : null }}>{{ $ruang->nama
                    }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="laboran">Laboran </label>
                <input type="text" name="laboran" id="laboran" class="form-control" value="{{ old('laboran') }}"
                  readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Bahan</h4>
        </div>
        <div class="card-body p-0">
          <div class="p-4">
            <a href="" class="btn btn-info float-right mb-3" data-toggle="modal" data-target="#modalBarang">Pilih
              Bahan</a>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama</th>
                  <th class="text-center">Stok Bahan</th>
                  <th>Jumlah Pinjam</th>
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
      </div>
      <div class="text-right">
        <button type="submit" class="btn btn-primary mr-1">
          <i class="fas fa-save"></i> Simpan
        </button>
        <button type="reset" class="btn btn-secondary">
          <i class="fas fa-undo"></i> Reset
        </button>
      </div>
    </form>
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
<script>
  var ruang_id = document.getElementById('ruang_id');
  var laboran = document.getElementById('laboran');
  function getlaboran() {
    if (ruang_id.value != "") {
      $.ajax({
        url: "{{ url('admin/pengambilan/ruang') }}" + "/" + ruang_id.value,
        type: "GET",
        dataType: "json",
        success: function(data) {
          laboran.value = data.laboran.nama; 
        },
      });
    }
  }
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
      $no = 1;
      $.ajax({
        url: "{{ url('admin/pengambilan/pilih') }}",
        type: "POST",
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
      url: "{{ url('peminjam/pilih') }}",
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
  var vTaAw = document.getElementById('tanggal_awal');
  var vTaAk = document.getElementById('tanggal_akhir');
  var vJaAw = document.getElementById('jam_awal');
  var vJaAk = document.getElementById('jam_akhir');

  var vKe = document.getElementById('keterangan');
  
  function checkData() {
    if (vTaAw.value == "" || vTaAk.value == "" || vJaAw.value == "" || vJaAk.value == "" || vKe.value == "" ) {
      swal("Error", "Lengkapi data terlebih dahulu!", "error");
    } else if (count === 0) {
        swal("Error", "Pilih barang terlebih dahulu!", "error");
    } else {
      $('#form-submit').submit();
    }
  }
</script>
@endsection