@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('admin/peminjaman') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Pinjam Barang</h1>
    </div>
    @if (session('status'))
      <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
        <div class="alert-icon">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-body">
          <div class="alert-title">Error!</div>
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          <p>
            @foreach (session('status') as $error)
              <span class="bullet"></span>&nbsp;{{ $error }}
              <br>
            @endforeach
          </p>
        </div>
      </div>
    @endif
    <div class="section-body">
      <form action="{{ url('admin/peminjaman') }}" method="POST" autocomplete="off" id="form-submit">
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Peminjam</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="selectgroup">
                    <label class="selectgroup-item">
                      <input type="radio" name="check" id="check" value="0" class="selectgroup-input"
                        onclick="click_radio()" {{ old('check', '0') == '0' ? 'checked' : '' }}>
                      <span class="selectgroup-button">Tambah Baru</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="check" id="check" value="1" class="selectgroup-input"
                        onclick="click_radio()" {{ old('check') == '1' ? 'checked' : '' }}>
                      <span class="selectgroup-button">Yang sudah ada</span>
                    </label>
                  </div>
                </div>
                <div id="layout_0">
                  <div class="form-group">
                    <label for="alamat">Nama Instansi *</label>
                    <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat') }}">
                  </div>
                  <div class="form-group">
                    <label for="nama">Nama Peminjam *</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
                  </div>
                  <div class="form-group">
                    <label for="telp">Nomor yang dapat dihubungi * </label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">+62</div>
                      </div>
                      <input type="text" class="form-control" name="telp" id="telp" value="{{ old('telp') }}"
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    </div>
                  </div>
                </div>
                <div id="layout_1">
                  <div class="form-group">
                    <label for="peminjam_id">Peminjam *</label>
                    <select class="form-control select2" name="peminjam_id" id="peminjam_id">
                      @foreach ($peminjams as $peminjam)
                        <option value="{{ $peminjam->id }}" {{ old('peminjam_id') == $peminjam->id }}>
                          {{ $peminjam->alamat }} - {{ $peminjam->nama }} (0{{ $peminjam->telp }})</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  <label for="lama">Lama Peminjaman <small>(Hari)</small> *</label>
                  <input type="number" name="lama" id="lama" class="form-control" value="{{ old('lama') }}">
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>Tambah Alat</h4>
              </div>
              <div class="card-body p-0">
                <div class="p-4">
                  <a href="" class="btn btn-info float-right mb-3" data-toggle="modal"
                    data-target="#modalBarang">Pilih Alat</a>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">No.</th>
                        <th>Nama</th>
                        <th class="text-center">Stok Barang</th>
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
            <div class="float-right">
              <button type="button" class="btn btn-primary" onclick="checkData()">Buat Pinjaman</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
  <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarang"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="m-0 font-weight-bold">Pilih Barang</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-right mb-3">
            <button type="button" class="btn btn-warning mt-1 text-white mr-1" id="uncheckAll">Uncheck
              Semua</button>
            <button type="button" class="btn btn-primary mt-1 text-white" id="addItem">Masukan
              Barang</button>
          </div>
          <table class="table table-hover" id="table-1">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Alat</th>
                <th>Ruang</th>
                <th class="text-center">Stok</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($barangs as $barang)
                <tr>
                  <td class="text-center pb-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="checkboxId" value="{{ $barang->id }}">
                    </div>
                  </td>
                  <td>{{ $barang->nama }}</td>
                  <td>{{ $barang->ruang->nama }}</td>
                  <td class="text-center">{{ $barang->normal }} {{ $barang->satuan->singkatan }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    var radioButtons = document.querySelectorAll('input[name="check"]');

    var selectedValue = "1";
    for (const radioButton of radioButtons) {
      if (radioButton.checked) {
        selectedValue = radioButton.value;
        break;
      }
    }

    console.log(selectedValue);

    if (selectedValue == '0') {
      layout_0.style.display = "inline";
      layout_1.style.display = "none";
    } else if (selectedValue == '1') {
      layout_0.style.display = "none";
      layout_1.style.display = "inline";
    }

    function click_radio() {
      for (const radioButton of radioButtons) {
        if (radioButton.checked) {
          selectedValue = radioButton.value;
          break;
        }
      }
      if (selectedValue == '0') {
        layout_0.style.display = "inline";
        layout_1.style.display = "none";
      } else if (selectedValue == '1') {
        layout_0.style.display = "none";
        layout_1.style.display = "inline";
      }
    }
    var checkboxes = document.querySelectorAll('#checkboxId');
    var count = 0;
    var listItem = [];
    var addItem = document.getElementById('addItem');
    for (var checkbox of checkboxes) {
      checkbox.addEventListener('click', function() {
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
      });
    };
    var dataItems = document.getElementById('dataItems');
    addItem.addEventListener('click', function() {
      if (listItem.length === 0) {
        alert("Pilih barang terlebih dahulu!");
      } else {
        $item = listItem;
        $no = 1;
        $.ajax({
          url: "{{ url('admin/peminjaman/pilih') }}",
          type: "GET",
          data: {
            "items": $item
          },
          dataType: "json",
          success: function(data) {
            if (data != null) {
              $("#dataItems").empty();
              $no = 1;
              $.each(data, function(key, value) {
                $("#dataItems").append("<tr>\
                          <td class='text-center'>" + $no++ + "</td>\
                          <td>" + value.nama + "</td>\
                          <td class='text-center'>" + value.normal + " " + value.satuan.singkatan + "</td>\
                          <td>\
                            <div class='input-group'>\
                              <input class='form-control' type='number' id='jumlahId' name='jumlah[" + key +
                  "]' oninput='this.value = !!this.value && Math.abs(this.value) >= 1 && !!this.value && Math.abs(this.value) <= " +
                  value.normal + " ? Math.abs(this.value) : null' value='1' required>\
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
                  $("#satuan" + key + "").append("<option value='6'>Pcs</option>");
                }
              });
              console.log(data);
            }
          },
        });
      }
    });
    var uncheckAll = document.getElementById('uncheckAll')
    uncheckAll.addEventListener('click', function() {
      $('input[type="checkbox"]:checked').prop('checked', false);
      listItem = [];
      $item = listItem;
      $.ajax({
        url: "{{ url('admin/peminjaman/pilih') }}",
        type: "GET",
        data: {
          "items": $item
        },
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
    var alamat = document.getElementById('alamat');
    var nama = document.getElementById('nama');
    var telp = document.getElementById('telp');
    var lama = document.getElementById('lama');

    function checkData() {
      if (selectedValue == '0') {
        if (alamat.value == "" || nama.value == "" || telp.value == "" || lama.value == "") {
          swal("Error", "Lengkapi data terlebih dahulu!", "error");
        } else if (count === 0) {
          swal("Error", "Pilih barang terlebih dahulu!", "error");
        } else {
          $('#form-submit').submit();
        }
      } else if (selectedValue == '1') {
        if (lama.value == "") {
          swal("Error", "Lengkapi data terlebih dahulu!", "error");
        } else if (count === 0) {
          swal("Error", "Pilih barang terlebih dahulu!", "error");
        } else {
          $('#form-submit').submit();
        }
      }
    }
  </script>
@endsection
