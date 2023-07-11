@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('peminjam/normal/peminjaman-new') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Pinjam Barang</h1>
    </div>
    <div class="section-body">
      <form action="{{ url('peminjam/normal/peminjaman-new') }}" method="POST" autocomplete="off" id="form-submit">
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Peminjam</h4>
              </div>
              @csrf
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="ketua">Peminjam</label>
                      <select class="form-control selectric" name="ketua" id="ketua">
                        <option value="{{ auth()->user()->kode }}">{{ auth()->user()->kode }} - {{ auth()->user()->nama }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="anggota">Anggota <small>(Kosongkan saja jika tidak diperlukan)</small></label>
                      <select class="form-control select2" name="anggota[]" id="anggota" multiple="">
                        @foreach ($peminjams as $peminjam)
                          <option value="{{ $peminjam->kode }}">{{ $peminjam->kode }} - {{ $peminjam->nama }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>Waktu Peminjaman</h4>
              </div>
              @csrf
              <div class="card-body">
                @php
                  $today = Carbon\Carbon::now()->format('Y-m-d');
                @endphp
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tanggal_awal">Tanggal Pinjam</label>
                      <input type="date" class="form-control @error('tanggal_awal') is-invalid @enderror"
                        name="tanggal_awal" id="tanggal_awal" min="{{ $today }}"
                        value="{{ old('tanggal_awal', $today) }}">
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
                        id="jam_awal" value="{{ old('jam_awal') }}" min="07:00:00" max="17:00:00">
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
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>Detail Peminjaman</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="matakuliah">Mata Kuliah *</label>
                      <input type="text" name="matakuliah" id="matakuliah"
                        class="form-control @error('matakuliah') is-invalid @enderror" value="{{ old('matakuliah') }}">
                      @error('matakuliah')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class=" col-md-6">
                    <div class="form-group">
                      <label for="dosen">Dosen Pengampu *</label>
                      <input type="text" name="dosen" id="dosen"
                        class="form-control @error('dosen') is-invalid @enderror" value="{{ old('dosen') }}">
                      @error('dosen')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class=" row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="ruang_id">Ruang Lab.</label>
                      <select class="form-control select2" id="ruang_id" name="ruang_id">
                        @foreach ($ruangs as $ruang)
                          <option value="{{ $ruang->id }}" {{ old('ruang_id') == $ruang->id ? 'selected' : '' }}>
                            {{ $ruang->nama }} ({{ ucfirst($ruang->prodi->nama) }})</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="keterangan">Keterangan</label>
                      <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
                      @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
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
            <div class="card">
              <div class="card-header">
                <h4>Tambah Bahan</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <textarea class="form-control" id="bahan" name="bahan" style="height: 120px"
                    placeholder="Masukan bahan yang dibutuhkan"></textarea>
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
    // var tanggalAwal = document.getElementById('tanggal_awal');
    // var tanggalAkhir = document.getElementById('tanggal_akhir');
    // var jamAwal = document.getElementById('jam_awal');
    // var jamAkhir = document.getElementById('jam_ahir');
    // var today = "{{ Carbon\Carbon::now()->format('Y-m-d') }}";
    // tanggalAkhir.value = today;
    // tanggalAkhir.min = today;
    // tanggalAwal.addEventListener('change', function() {
    //   if (this.value != today) {
    //     tanggalAkhir.value = "";
    //   }
    //   tanggalAkhir.setAttribute('min', this.value);
    // });

    var dataItems = document.getElementById('dataItems');

    var item = @json(session('item'));
    var jumlah = @json(session('jumlah'));

    if (item != null) {
      const no = 1;
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
          data_item(no, barang);
        }
      } else {
        const empty_item = "<tr>";
        empty_item += "<td colspan='5' class='text-center'>- Belum ada barang yang dipilih -</td>";
        empty_item += "</tr>";
        $("#dataItems").append(empty_item);
      }
    }

    var checkboxes = document.querySelectorAll('#checkboxId');
    var count = 0;

    var item_id = @json(session('item_id'));

    var listItem = [];
    if (item_id != null) {
      for (let i = 0; i < item_id.length; i++) {
        const element = item_id[i].toString();
        listItem.push(element);
      }
    }

    var addItem = document.getElementById('addItem');
    for (var checkbox of checkboxes) {
      checkbox.addEventListener('click', function() {
        if (this.checked == true) {
          listItem.push(this.value);
        } else {
          listItem = listItem.filter(e => e !== this.value);
        }
        if (listItem.length > 0) {
          addItem.setAttribute("data-toggle", "modal");
          addItem.setAttribute("data-target", "#modalBarang");
        } else {
          addItem.removeAttribute("data-toggle");
          addItem.removeAttribute("data-target");
        }
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
              const no = 1;
              $.each(data, function(key, value) {
                data_item(no, key);
              });
            }
          },
        });
      }
    });

    var uncheckAll = document.getElementById('uncheckAll');
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
    var vTaAw = document.getElementById('tanggal_awal');
    var vTaAk = document.getElementById('tanggal_akhir');
    var vJaAw = document.getElementById('jam_awal');
    var vJaAk = document.getElementById('jam_akhir');

    function checkData() {
      if (vTaAw.value == "" || vTaAk.value == "" || vJaAw.value == "" || vJaAk.value == "") {
        swal("Error", "Lengkapi data terlebih dahulu!", "error");
      } else if (count === 0) {
        swal("Error", "Pilih barang terlebih dahulu!", "error");
      } else {
        $('#form-submit').submit();
      }
    }

    function data_item(no, data) {
      const data_item = "<tr>";
      data_item += "<td class='text-center'>" + no++ + "</td>";
      data_item += "<td>" + data.nama + "</td>";
      data_item += "<td>" + data.ruang.nama + "</td>";
      data_item += "<td>" + data.normal + " " + data.satuan.singkatan + "</td>";
      data_item += "<td>";
      data_item += "<div class='input-group'>";
      data_item += "<input class='form-control' type='number' id='jumlahId' name='jumlah[" + data.id +
        "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= " +
        data.normal + " ? Math.abs(this.value) : null' value=" + value + " required>";
      data_item += "<input type='hidden' name='barang_id[" + data.id + "]' value='" + data.id +
        "' class='form-control'>";
      data_item += "</div>";
      data_item += "</td>";
      data_item += "</tr>";
      $("#dataItems").append(data_item);
    }
  </script>
@endsection
