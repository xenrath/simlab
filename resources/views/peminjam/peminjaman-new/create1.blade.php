@extends('layouts.app')

@section('title', 'Buat Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('peminjam/normal/peminjaman-new') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Peminjaman</h1>
    </div>
    @if (session('error_peminjaman') || session('empty_barang'))
      <div class="alert alert-danger alert-dismissible show fade">
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
          @if (session('empty_kelompok'))
            <div class="alert-title">Kelompok</div>
            <button class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
            @if (session('error_kelompok'))
              <p>
                @foreach (session('error_kelompok') as $error)
                  <span class="bullet"></span>&nbsp;{{ $error }}
                  <br>
                @endforeach
              </p>
            @else
              <p>
                @foreach (session('empty_kelompok') as $error)
                  <span class="bullet"></span>&nbsp;{{ $error }}
                  <br>
                @endforeach
              </p>
            @endif
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
      <form action="{{ url('peminjam/normal/peminjaman-new') }}" method="POST" autocomplete="off" id="form-submit">
        @csrf
        <div class="card">
          <div class="card-header">
            <h4>Buat Peminjaman</h4>
          </div>
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="praktik_id">Jenis Praktik</label>
              <select class="form-control selectric" name="praktik_id" id="praktik_id" onchange="get_praktik()">
                @foreach ($praktiks as $praktik)
                  <option value="{{ $praktik->id }}" {{ old('praktik_id') == $praktik->id ? 'selected' : '' }}>
                    {{ $praktik->nama }}</option>
                @endforeach
              </select>
            </div>
            <div id="layout_ketua">
              <div class="form-group">
                <label for="ketua">Ketua</label>
                <select class="form-control selectric" name="ketua" id="ketua">
                  <option value="{{ auth()->user()->kode }}">{{ auth()->user()->kode }} - {{ auth()->user()->nama }}
                  </option>
                </select>
              </div>
            </div>
            <div id="layout_anggota">
              <div class="form-group">
                <label for="anggota">Anggota</label>
                <select class="form-control select2" name="anggota[]" id="anggota" multiple="">
                  @foreach ($peminjams as $peminjam)
                    <option value="{{ $peminjam->kode }}"
                      {{ collect(old('anggota'))->contains($peminjam->kode) ? 'selected' : '' }}>{{ $peminjam->kode }} -
                      {{ $peminjam->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div id="layout_tanggal">
              <div class="form-group">
                <label for="tanggal">Waktu Praktik</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}"
                  value="{{ old('tanggal') }}">
              </div>
            </div>
            <div id="layout_lama">
              <div class="form-group">
                <label for="lama">Lama Peminjaman (Hari)</label>
                <input type="number" name="lama" id="lama" class="form-control" value="{{ old('lama') }}">
              </div>
            </div>
            <div id="layout_jam">
              <div class="form-group">
                <label for="jam">Jam Praktik</label>
                <select class="form-control selectric" name="jam" id="jam" onchange="get_custom_jam()">
                  <option value="">- Pilih -</option>
                  <option value="08.00-09.40" {{ old('jam') == '08.00-09.40' ? 'selected' : '' }}>08.00-09.40</option>
                  <option value="09.40-11.20" {{ old('jam') == '09.40-11.20' ? 'selected' : '' }}>09.40-11.20</option>
                  <option value="12.30-14.10" {{ old('jam') == '12.30-14.10' ? 'selected' : '' }}>12.30-14.10</option>
                  <option value="14.10-15.40" {{ old('jam') == '14.10-15.40' ? 'selected' : '' }}>14.10-15.40</option>
                  <option value="lainnya" {{ old('jam') == 'lainnya' ? 'selected' : '' }}>Jam Lainnya</option>
                </select>
              </div>
            </div>
            <div id="layout_custom_jam">
              <div class="form-group">
                <label for="jam_awal">Jam Awal</label>
                <input type="time" name="jam_awal" id="jam_awal" class="form-control" value="{{ old('jam_awal') }}">
              </div>
              <div class="form-group">
                <label for="jam_akhir">Jam Akhir</label>
                <input type="time" name="jam_akhir" id="jam_akhir" class="form-control"
                  value="{{ old('jam_akhir') }}">
              </div>
            </div>
            <div id="layout_ruang_id">
              <div class="form-group">
                <label for="ruang_id">Ruang (Lab)</label>
                <select class="form-control select2" id="ruang_id" name="ruang_id">
                  <option value="">- Pilih -</option>
                  @foreach ($ruangs as $ruang)
                    <option value="{{ $ruang->id }}" {{ old('ruang_id') == $ruang->id ? 'selected' : '' }}>
                      {{ $ruang->nama }} ({{ ucfirst($ruang->prodi->singkatan) }})</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div id="layout_matakuliah">
              <div class="form-group">
                <label for="matakuliah">Mata Kuliah - Praktik</label>
                <input type="text" name="matakuliah" id="matakuliah" class="form-control"
                  value="{{ old('matakuliah') }}">
              </div>
            </div>
            <div id="layout_dosen">
              <div class="form-group">
                <label for="dosen">Dosen Pengampu</label>
                <input type="text" name="dosen" id="dosen" class="form-control"
                  value="{{ old('dosen') }}">
              </div>
            </div>
            <div id="layout_keterangan">
              <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control"
                  placeholder="nama kelas / tempat" value="{{ old('keterangan') }}">
              </div>
            </div>
            <div id="layout_laboran_id">
              <div class="form-group">
                <label for="laboran_id">Laboran Penerima</label>
                <select class="form-control select2" id="laboran_id" name="laboran_id">
                  <option value="">- Pilih -</option>
                  @foreach ($laborans as $laboran)
                    <option value="{{ $laboran->id }}" {{ old('laboran_id') == $laboran->id ? 'selected' : '' }}>
                      {{ $laboran->nama }}</option>
                  @endforeach
                </select>
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
                data-target="#modalBarang">Pilih
                Alat</a>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" width="20">No.</th>
                    <th>Nama Alat</th>
                    <th width="120">Stok</th>
                    <th width="240">Jumlah</th>
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
                placeholder="masukan bahan yang dibutuhkan"></textarea>
            </div>
          </div>
        </div>
        <div class="float-right">
          <button type="submit" class="btn btn-primary">Buat Pinjaman</button>
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
              @php
                $item_id = [];
                if (session('item_id')) {
                    foreach (session('item_id') as $i) {
                        array_push($item_id, $i);
                    }
                }
              @endphp
              @foreach ($barangs as $barang)
                <tr>
                  <td class="text-center pb-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="checkboxId" value="{{ $barang->id }}"
                        {{ in_array($barang->id, $item_id) ? 'checked' : '' }}>
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
    var praktik_id = document.getElementById('praktik_id');

    var layout_ketua = document.getElementById('layout_ketua');
    var layout_anggota = document.getElementById('layout_anggota');
    var layout_tanggal = document.getElementById('layout_tanggal');
    var tanggal = document.getElementById('tanggal');
    var layout_lama = document.getElementById('layout_lama');
    var lama = document.getElementById('lama');
    var layout_jam = document.getElementById('layout_jam');
    var jam = document.getElementById('jam');
    var layout_custom_jam = document.getElementById('layout_custom_jam');
    var layout_ruang_id = document.getElementById('layout_ruang_id');
    var ruang_id = document.getElementById('ruang_id');
    var layout_matakuliah = document.getElementById('layout_matakuliah');
    var matakuliah = document.getElementById('matakuliah');
    var layout_dosen = document.getElementById('layout_dosen');
    var dosen = document.getElementById('dosen');
    var layout_keterangan = document.getElementById('layout_keterangan');
    var keterangan = document.getElementById('keterangan');
    var layout_laboran_id = document.getElementById('layout_laboran_id');
    var laboran_id = document.getElementById('laboran_id');

    if (praktik_id.value == '1') {
      layout_ketua.style.display = 'inline';
      layout_anggota.style.display = 'inline';
      layout_tanggal.style.display = 'inline';
      layout_lama.style.display = 'none';
      layout_jam.style.display = 'inline';
      layout_ruang_id.style.display = 'inline';
      layout_matakuliah.style.display = 'inline';
      layout_dosen.style.display = 'inline';
      layout_keterangan.style.display = 'inline';
      layout_laboran_id.style.display = 'none';
    } else if (praktik_id.value == '2') {
      layout_ketua.style.display = 'inline';
      layout_anggota.style.display = 'inline';
      layout_tanggal.style.display = 'inline';
      layout_lama.style.display = 'none';
      layout_jam.style.display = 'inline';
      layout_ruang_id.style.display = 'none';
      layout_matakuliah.style.display = 'inline';
      layout_dosen.style.display = 'inline';
      layout_keterangan.style.display = 'inline';
      layout_laboran_id.style.display = 'inline';
    } else if (praktik_id.value == '3') {
      layout_ketua.style.display = 'none';
      layout_anggota.style.display = 'none';
      layout_tanggal.style.display = 'none';
      layout_lama.style.display = 'inline';
      layout_jam.style.display = 'none';
      layout_ruang_id.style.display = 'none';
      layout_matakuliah.style.display = 'inline';
      layout_dosen.style.display = 'inline';
      layout_keterangan.style.display = 'inline';
      layout_laboran_id.style.display = 'inline';
    }

    function get_praktik() {
      console.log(praktik_id.value);
      if (praktik_id.value == '1') {
        layout_ketua.style.display = 'inline';
        layout_anggota.style.display = 'inline';
        layout_tanggal.style.display = 'inline';
        layout_lama.style.display = 'none';
        layout_jam.style.display = 'inline';
        layout_ruang_id.style.display = 'inline';
        layout_matakuliah.style.display = 'inline';
        layout_dosen.style.display = 'inline';
        layout_keterangan.style.display = 'inline';
        layout_laboran_id.style.display = 'none';
        if (praktik_id.value != '3') {
          if (jam.value == 'lainnya') {
            layout_custom_jam.style.display = "inline";
          } else {
            layout_custom_jam.style.display = "none";
          }
        } else {
          layout_custom_jam.style.display = "none";
        }
      } else if (praktik_id.value == '2') {
        layout_ketua.style.display = 'inline';
        layout_anggota.style.display = 'inline';
        layout_tanggal.style.display = 'inline';
        layout_lama.style.display = 'none';
        layout_jam.style.display = 'inline';
        layout_ruang_id.style.display = 'none';
        layout_matakuliah.style.display = 'inline';
        layout_dosen.style.display = 'inline';
        layout_keterangan.style.display = 'inline';
        layout_laboran_id.style.display = 'inline';
        if (praktik_id.value != '3') {
          if (jam.value == 'lainnya') {
            layout_custom_jam.style.display = "inline";
          } else {
            layout_custom_jam.style.display = "none";
          }
        } else {
          layout_custom_jam.style.display = "none";
        }
      } else if (praktik_id.value == '3') {
        layout_ketua.style.display = 'none';
        layout_anggota.style.display = 'none';
        layout_tanggal.style.display = 'none';
        layout_lama.style.display = 'inline';
        layout_jam.style.display = 'none';
        layout_ruang_id.style.display = 'none';
        layout_matakuliah.style.display = 'inline';
        layout_dosen.style.display = 'inline';
        layout_keterangan.style.display = 'inline';
        layout_laboran_id.style.display = 'inline';
        if (praktik_id.value != '3') {
          if (jam.value == 'lainnya') {
            layout_custom_jam.style.display = "inline";
          } else {
            layout_custom_jam.style.display = "none";
          }
        } else {
          layout_custom_jam.style.display = "none";
        }
      }
    }

    if (praktik_id.value != '3') {
      if (jam.value == 'lainnya') {
        layout_custom_jam.style.display = "inline";
      } else {
        layout_custom_jam.style.display = "none";
      }
    } else {
      layout_custom_jam.style.display = "none";
    }

    function get_custom_jam() {
      if (praktik_id.value != '3') {
        if (jam.value == 'lainnya') {
          layout_custom_jam.style.display = "inline";
        } else {
          layout_custom_jam.style.display = "none";
        }
      } else {
        layout_custom_jam.style.display = "none";
      }
    }

    var tanggalAwal = document.getElementById('tanggal_awal');
    var tanggalAkhir = document.getElementById('tanggal_akhir');
    var jamAwal = document.getElementById('jam_awal');
    var jamAkhir = document.getElementById('jam_ahir');
    var today = "{{ Carbon\Carbon::now()->format('Y-m-d') }}";

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
    var item = @json(session('item'));
    console.log(item);
    var jumlah = @json(session('jumlah'));

    if (item != null) {
      var no = 0;
      $("#dataItems").empty();
      if (jumlah.length > 0) {
        for (let i = 0; i < item.length; i++) {
          var barang = item[i];
          no = no + 1;
          data_item(no, barang);
        }
      } else {
        var empty_item = "<tr>";
        empty_item += "<td colspan='5' class='text-center'>- Belum ada barang yang dipilih -</td>";
        empty_item += "</tr>";
        $("#dataItems").append(empty_item);
      }
    }

    addItem.addEventListener('click', function() {
      if (listItem.length === 0) {
        alert("Pilih barang terlebih dahulu!");
      } else {
        $item = listItem;
        console.log($item);
        $.ajax({
          url: "{{ url('peminjam/pilih') }}",
          type: "GET",
          data: {
            "items": $item
          },
          dataType: "json",
          success: function(data) {
            $("#dataItems").empty();
            if (data != null) {
              var no = 0;
              $.each(data, function(key, value) {
                no = no + 1;
                data_item(no, value);
              });
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
            const empty_item = "<tr>";
            empty_item += "<td colspan='5' class='text-center'>- Belum ada barang yang dipilih -</td>";
            empty_item += "</tr>";
            $("#dataItems").append(empty_item);
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
      var value = "1";
      if (item != null) {
        for (let i = 0; i < jumlah.length; i++) {
          const element = jumlah[i];
          if (element['barang_id'] == data.id) {
            value = element['jumlah'];
            console.log(value);
          }
        }
      }
      var data_item = "<tr>";
      data_item += "<td class='text-center'>" + no + "</td>";
      data_item += "<td>" + data.nama + "</td>";
      data_item += "<td>" + data.normal + " " + data.satuan.singkatan + "</td>";
      data_item += "<td>";
      data_item += "<div class='input-group'>";
      data_item += "<input class='form-control' type='number' id='jumlahId' name='jumlah[" +
        data.id +
        "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= " +
        data.normal + " ? Math.abs(this.value) : null' value=" + value + ">";
      data_item += "<input type='hidden' name='barang_id[" + data.id + "]' value='" + data.id +
        "' class='form-control'>";
      data_item += "</div>";
      data_item += "</td>";
      data_item += "</tr>";
      $("#dataItems").append(data_item);
    }
  </script>
@endsection
