@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('peminjam/normal/peminjaman') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Pinjam Barang</h1>
    </div>
    @if (session('error_peminjaman') || session('empty_barang'))
      <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
        <div class="alert-body">
          @if (session('error_peminjaman'))
            <div class="alert-title">Peminjaman</div>
            <button class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
            <p>
              @foreach (session('error_peminjaman') as $error)
                <span class="bullet"></span>&nbsp;{{ $error }}
                <br>
              @endforeach
            </p>
            <div class="mb-2"></div>
          @endif
          @if (session('empty_barang'))
            <div class="alert-title">Barang</div>
            <button class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
            <p>
              @foreach (session('empty_barang') as $error)
                <span class="bullet"></span>&nbsp;{{ $error }}
                <br>
              @endforeach
            </p>
          @endif
        </div>
      </div>
    @endif
    <div class="section-body">
      <form action="{{ url('peminjam/normal/peminjaman') }}" method="POST" autocomplete="off" id="form-submit">
        @csrf
        <div class="card">
          <div class="card-header">
            <h4>Peminjaman</h4>
          </div>
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="matakuliah">Mata Kuliah</label>
              <input type="text" name="matakuliah" id="matakuliah" class="form-control"
                value="{{ old('matakuliah') }}">
              @error('matakuliah')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label for="dosen">Dosen Pengampu</label>
              <input type="text" name="dosen" id="dosen" class="form-control" value="{{ old('dosen') }}">
              @error('dosen')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label for="ruang_id">Ruang Lab.</label>
              <select class="form-control selectric" id="ruang_id" name="ruang_id">
                <option value="">- Pilih Ruang -</option>
                @foreach ($ruangs as $ruang)
                  <option value="{{ $ruang->id }}" {{ old('ruang_id') == $ruang->id ? 'selected' : '' }}>
                    {{ $ruang->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4>Alat</h4>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <a href="" class="btn btn-info float-right mb-3" data-toggle="modal"
                data-target="#modalBarang">Pilih</a>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Nama</th>
                    <th>Ruang</th>
                    <th>Stok</th>
                    <th>Jumlah</th>
                  </tr>
                </thead>
                <tbody id="dataItems">
                  <tr>
                    <td colspan="5" class="text-center">Belum ada alat yang dipilih</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4>Bahan</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <textarea class="form-control" id="bahan" name="bahan" style="height: 120px"
                placeholder="masukan bahan yang dibutuhkan"></textarea>
            </div>
          </div>
        </div>
        <div class="float-right">
          <button type="button" class="btn btn-primary" onclick="checkData()">Buat Pinjaman</button>
        </div>
      </form>
    </div>
  </section>
  <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarang" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="m-0 font-weight-bold">Pilih Alat</h6>
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
    var dataItems = document.getElementById('dataItems');

    var item = @json(session('item'));
    var jumlah = @json(session('jumlah'));

    if (item != null) {
      $no = 1;
      $("#dataItems").empty();
      if (jumlah.length > 0) {
        for (let i = 0; i < item.length; i++) {
          var barang = item[i];
          var value = "1";
          for (let i = 0; i < jumlah.length; i++) {
            const element = jumlah[i];
            if (element['barang_id'] == barang.id) {
              value = element['jumlah'];
              console.log(value);
            }
          }
          $("#dataItems").append("<tr>\
            <td class='text-center'>" + $no++ + "</td>\
            <td>" + barang.nama + "</td>\
            <td>" + barang.ruang.nama + "</td>\
            <td>" + barang.normal + " " + barang.satuan.singkatan + "</td>\
            <td>\
              <div class='input-group'>\
                <input class='form-control' type='number' id='jumlahId' name='jumlah[" +
            barang
            .id +
            "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= " +
            barang.normal + " ? Math.abs(this.value) : null' value=" + value + " required>\
              <input type='hidden' name='barang_id[" + barang.id + "]' value='" + barang
            .id + "' class='form-control'>\
              </div>\
            </td>\
          </tr>");
        }
      } else {
        $("#dataItems").append("<tr>\
          <td colspan='5' class='text-center'>- Belum ada barang yang dipilih -</td>\
        </tr>");
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
        document.getElementById("countChecked").textContent = listItem.length;
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
          url: "{{ url('peminjam/pilih') }}",
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
                                <td>" + value.ruang.nama + "</td>\
                                <td>" + value.normal + " " + value.satuan.singkatan + "</td>\
                                <td>\
                                  <div class='input-group'>\
                                    <input class='form-control' type='number' id='jumlahId' name='jumlah[" + key +
                  "]' oninput='this.value = !!this.value && Math.abs(this.value) >= 1 && !!this.value && Math.abs(this.value) <= " +
                  value.normal + " ? Math.abs(this.value) : null' value='1' required>\
                                    <input type='hidden' name='barang_id[" + key + "]' value='" + value.id + "' class='form-control'>\
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
    uncheckAll.addEventListener('click', function() {
      $('input[type="checkbox"]:checked').prop('checked', false);
      listItem = [];
      document.getElementById("countChecked").textContent = listItem.length;
      $item = listItem;
      $.ajax({
        url: "{{ url('peminjam/pilih') }}",
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

    function checkData() {
      $('#form-submit').submit();
      // if (vTaAw.value == "" || vTaAk.value == "" || vJaAw.value == "" || vJaAk.value == "") {
      //   swal("Error", "Lengkapi data terlebih dahulu!", "error");
      // } else if (count === 0) {
      //   swal("Error", "Pilih barang terlebih dahulu!", "error");
      // } else {
      // }
    }
  </script>
@endsection
