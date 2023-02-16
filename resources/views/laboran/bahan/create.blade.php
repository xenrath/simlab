@extends('layouts.app')

@section('title', 'Tambah Pengambilan Bahan')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/laboran/pengambilan') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Pengambilan Bahan</h1>
  </div>
  <form action="{{ url('laboran/bahan') }}" method="POST" id="form-submit">
    @csrf
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Ruangan</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="ruang_id">Ruang / Lab *</label>
                <select class="form-control select2" name="ruang_id" id="ruang_id" onchange="getruang()">
                  <option value="">- Pilih -</option>
                  @foreach ($ruangs as $ruang)
                  <option value="{{ $ruang->id }}">{{ $ruang->nama }} ({{ ucfirst($ruang->prodi->nama) }})</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6" id="detail_ruang" style="display: none">
              <div class="border rounded p-3">
                <table class="w-100">
                  <tr>
                    <th>Nama Ruang</th>
                    <td>:</td>
                    <td>
                      <span id="span_ruang"></span>
                    </td>
                  </tr>
                  <tr>
                    <th>Prodi</th>
                    <td>:</td>
                    <td>
                      <span id="span_prodi" style="text-transform: capitalize"></span>
                    </td>
                  </tr>
                  <tr>
                    <th>Laboran</th>
                    <td>:</td>
                    <td>
                      <span id="span_laboran"></span>
                    </td>
                  </tr>
                  <tr>
                    <th>Tempat</th>
                    <td>:</td>
                    <td>
                      <span id="span_tempat"></span>
                    </td>
                  </tr>
                </table>
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
          <div class="p-4 float-right">
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalBahan">
              <i class="far fa-check-square"></i>&nbsp; Pilih Bahan
            </button>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama Bahan</th>
                  <th>Stok</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody id="dataItems">
                <tr id="dataEmpty">
                  <td colspan="4" class="text-center">- Data tidak ditemukan -</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="float-right">
        <button type="button" class="btn btn-primary" onclick="check()">
          <i class="fas fa-save"></i> Simpan
        </button>
        <button type="reset" class="btn btn-secondary">
          <i class="fas fa-undo"></i> Reset
        </button>
      </div>
    </div>
  </form>
</section>
<div class="modal fade" id="modalBahan" tabindex="-1" role="dialog" aria-labelledby="modalBahan" aria-hidden="true">
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
            <button type="button" class="btn btn-warning mt-1 text-white" id="uncheckAll">Uncheck
              Semua</button>
            <button type="button" class="btn btn-primary mt-1 text-white ml-1" id="addItem">Masukan
              Barang</button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover" id="table-1">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Nama Bahan</th>
                <th>Stok</th>
                <th>Satuan</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bahans as $bahan)
              <tr>
                <td class="text-center pb-4">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkboxId" value="{{ $bahan->id }}">
                  </div>
                </td>
                <td>{{ $bahan->nama }}</td>
                <td>{{ $bahan->stok }}</td>
                <td>{{ $bahan->satuan->singkatan }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  var ruang_id = document.getElementById('ruang_id');
  var detail_ruang = document.getElementById('detail_ruang');
  var span_ruang = document.getElementById('span_ruang');
  var span_prodi = document.getElementById('span_prodi');
  var span_laboran = document.getElementById('span_laboran');
  var span_tempat = document.getElementById('span_tempat');

  function getruang() {
    $.ajax({
      url: "{{ url('laboran/bahan/ruang') }}" + "/" + ruang_id.value,
      type: "GET",
      dataType: "json",
      success: function(ruang) {
        if (ruang != null) {
          detail_ruang.style.display = "inline";
          span_ruang.textContent = ruang.nama;
          var prodi = "-";
          if (ruang.kode != "02") {
            prodi = ruang.prodi.nama;
          }
          span_prodi.textContent = prodi;
          span_laboran.textContent = ruang.laboran.nama;
          span_tempat.textContent = ruang.tempat.nama;
        } else {
          detail_ruang.style.display = "none";
        }
      },
    });
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
      console.log('ahhaha');
      $item = listItem;
      $no = 1;
      $.ajax({
        url: "{{ url('laboran/bahan/pilih') }}",
        type: "GET",
        data: { "items": $item },
        dataType: "json",
        success: function(data) {
          if (data != null) {
            $('#dataItems').empty();
            $no = 1;
            $.each(data, function (key, value) {
              $("#dataItems").append("<tr>\
                <td class='text-center'>" + $no++ + "</td>\
                <td>" + value.nama + "</td>\
                <td>" + value.stok + " " + value.satuan.singkatan + "</td>\
                <td>\
                  <div class='input-group'>\
                    <input class='form-control' type='number' id='jumlahId' name='jumlah[" + key + "]' oninput='this.value = !!this.value && Math.abs(this.value) >= 1 && !!this.value && Math.abs(this.value) <= " + value.stok + " ? Math.abs(this.value) : null' value='1' required>\
                    <input type='hidden' name='bahan_id[" + key + "]' value='" + value.id + "' class='form-control'>\
                    <select class='custom-select' id='satuan" + key + "' name='satuan[" + key + "]'>\
                      <option value=" + value.satuan.id + ">" + value.satuan.singkatan + "</option>\
                    </select>\
                  </div>\
                </td>\
              </tr>");
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
  var ruang_id = document.getElementById('ruang_id');
  function check() {
    if (ruang_id.value == "") {
      swal("Error", "Ruang belum dipilih!", "error");
    } else if (count === 0) {
      swal("Error", "Bahan belum ditambahkan!", "error");
    } else {
      $('#form-submit').submit();
    }
  }
</script>
@endsection