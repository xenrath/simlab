@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <button type="button" class="btn btn-secondary" onclick="submit()">
          <i class="fas fa-arrow-left"></i>
        </button>
      </div>
      <h1>Pinjam Barang</h1>
    </div>
    @if (session('kelompok'))
      <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
          <div class="alert-title">GAGAL !</div>
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          <p>
            @foreach (session('kelompok') as $error)
              <span class="bullet"></span>&nbsp;{{ strtoupper($error) }}
              <br>
            @endforeach
          </p>
        </div>
      </div>
    @endif
    <div class="section-body">
      <form action="{{ url('peminjam/pinjam/kelompok/' . $pinjam->id) }}" method="POST" autocomplete="off"
        id="form_pinjam">
        @csrf
        @method('put')
        <div class="row">
          <div class="col-12">
            <div class="card border">
              <div class="card-header">
                <h4>Waktu Peminjaman</h4>
              </div>
              @csrf
              <div class="card-body">
                @php
                  $tanggal_awal = $pinjam->tanggal_awal;
                  $today = Carbon\Carbon::now()->format('Y-m-d');
                  if ($tanggal_awal) {
                      $tawal = $tanggal_awal;
                  } else {
                      $tawal = $today;
                  }
                @endphp
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tanggal_awal">Tanggal Awal</label>
                      <input type="date" class="form-control @error('tanggal_awal') is-invalid @enderror"
                        name="tanggal_awal" id="tanggal_awal" min="{{ $today }}"
                        value="{{ old('tanggal_awal', $tawal) }}">
                      @error('tanggal_awal')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tanggal_akhir">Tanggal Akhir</label>
                      <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror"
                        name="tanggal_akhir" id="tanggal_akhir"
                        value="{{ old('tanggal_akhir', $pinjam->tanggal_kembali) }}">
                      @error('tanggal_akhir')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card border">
              <div class="card-header">
                <h4>Kelompok</h4>
              </div>
              <div class="card-body p-0">
                <div class="p-4">
                  <button type="button" class="btn btn-info float-right mb-3" data-toggle="modal"
                    data-target="#modalKelompok">
                    <i class="fa fa-plus"></i>&nbsp; Tambah Kelompok
                  </button>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">No.</th>
                        <th>Nama</th>
                        <th>Tim</th>
                        <th>Waktu</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody id="tbodykelompok">
                      @forelse ($kelompoks as $kelompok)
                        <tr id="rowkelompok{{ $kelompok->id }}">
                          <td class="text-center align-top py-3">{{ $loop->iteration }}</td>
                          <td class="align-top py-3">{{ $kelompok->nama }}</td>
                          <td class="py-3">
                            <span class="bullet"></span>&nbsp;{{ $kelompok->m_ketua->nama }} (Ketua)<br>
                            @foreach ($kelompok->anggota as $anggota)
                              <span
                                class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                              <br>
                            @endforeach
                          </td>
                          <td class="align-top py-3">
                            {{ $kelompok->shift }} ({{ $kelompok->jam }})
                          </td>
                          <td class="align-top py-3">
                            <button type="button" class="btn btn-danger" onclick="hapusKelompok({{ $kelompok->id }})">
                              <i class="fa fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="5" class="text-center">- Kelompok belum ditambahkan -</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card mt-3">
              <div class="card-header">
                <h4>Detail Peminjaman</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="matakuliah">Mata Kuliah *</label>
                      <input type="text" name="matakuliah" id="matakuliah"
                        class="form-control @error('matakuliah') is-invalid @enderror"
                        value="{{ old('matakuliah', $pinjam->matakuliah) }}">
                      @error('matakuliah')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class=" col-md-6">
                    <div class="form-group">
                      <label for="dosen">Dosen Pengampu *</label>
                      <input type="text" name="dosen" id="dosen"
                        class="form-control @error('dosen') is-invalid @enderror"
                        value="{{ old('dosen', $pinjam->dosen) }}">
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
                      <select class="form-control selectric @error('ruang_id') is-invalid @enderror" id="ruang_id"
                        name="ruang_id">
                        <option value="">- Pilih Ruang -</option>
                        @forelse ($ruangs as $ruang)
                          <option value="{{ $ruang->id }}"
                            {{ old('ruang_id', $pinjam->ruang_id) == $ruang->id ? 'selected' : '' }}>
                            {{ $ruang->nama }}</option>
                        @empty
                          <option value="" class="text-center" disabled>Ruang / Lab. tidak ditemukan</option>
                        @endforelse
                      </select>
                      @error('ruang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @if (session('barang'))
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
                    @foreach (session('barang') as $error)
                      <span class="bullet"></span>&nbsp;{{ $error }}
                      <br>
                    @endforeach
                  </p>
                </div>
              </div>
            @endif
            <div class="card mt-3">
              <div class="card-header">
                <h4>Daftar Barang</h4>
              </div>
              <div class="card-body p-0">
                <div class="p-4">
                  <a href="" class="btn btn-info float-right mb-3" data-toggle="modal"
                    data-target="#modalBarang">
                    <i class="far fa-check-square"></i>&nbsp; Pilih Barang
                  </a>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">No.</th>
                        <th>Nama</th>
                        <th>Stok Barang</th>
                        <th>Jumlah Pinjam</th>
                      </tr>
                    </thead>
                    <tbody id="dataItems">
                      @forelse ($detailpinjams as $detailpinjam)
                        <tr>
                          <td class="text-center">{{ $loop->iteration }}</td>
                          <td>{{ $detailpinjam->barang->nama }}</td>
                          <td>{{ $detailpinjam->barang->normal }} {{ $detailpinjam->barang->satuan->singkatan }}</td>
                          <td>{{ $detailpinjam->jumlah }} {{ $detailpinjam->satuan->singkatan }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="5" class="text-center">Belum ada barang yang dipilih</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card mt-3">
              <div class="card-header">
                <h4>Daftar Bahan</h4>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="bahan">Bahan-bahan</label>
                  <textarea class="form-control" id="bahan" name="bahan" style="height: 120px">{{ old('bahan', $pinjam->bahan) }}</textarea>
                </div>
              </div>
            </div>
            <div class="float-right">
              {{-- <button type="button" class="btn btn-primary" onclick="checkData()">Buat Pinjaman</button> --}}
              <button type="button" class="btn btn-primary" onclick="submit()">
                <i class="fa fa-save"></i>&nbsp; Simpan
              </button>
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
                  <th>Nama Barang</th>
                  <th>Ruang</th>
                  <th class="text-center">Jumlah Stok</th>
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
  </div>
  <div class="modal fade" id="modalKelompok" role="dialog" aria-labelledby="modalKelompok" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="font-weight-bold">Kelompok</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ url('peminjam/kelompok') }}" method="POST" autocomplete="off">
          @csrf
          <div class="modal-body">
            <input type="hidden" class="form-control" name="pinjam_id" id="pinjam_id" value="{{ $pinjam->id }}">
            <div class="form-group">
              <label for="nama_kelompok">Nama Kelompok</label>
              <input type="text" class="form-control" name="nama_kelompok" id="nama_kelompok">
            </div>
            <div class="form-group">
              <label for="ketua_kelompok">Ketua</label>
              <select class="form-control select2" name="ketua_kelompok" id="ketua_kelompok">
                <option value="">Pilih Ketua</option>
                @foreach ($peminjams as $peminjam)
                  <option value="{{ $peminjam->kode }}" {{ $peminjam->kode == auth()->user()->kode ? 'selected' : '' }}>
                    {{ $peminjam->kode }} - {{ $peminjam->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="anggota_kelompok">Anggota</label>
              <select class="form-control select2" name="anggota_kelompok[]" id="anggota_kelompok" multiple="">
                @foreach ($peminjams as $peminjam)
                  <option value="{{ $peminjam->kode }}">{{ $peminjam->kode }} - {{ $peminjam->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="shift">Shift</label>
              <select class="form-control select2" name="shift" id="shift">
                <option value="">- Pilih Shift -</option>
                <option value="Shift 1">Shift 1</option>
                <option value="Shift 2">Shift 2</option>
                <option value="Shift 3">Shift 3</option>
              </select>
            </div>
            <div class="form-group">
              <label for="jam">Jam</label>
              <input type="time" class="form-control" name="jam" id="jam">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    // const anggota = document.getElementById('anggota');
    // anggota.addEventListener('change', (e) => {
    //   const options = e.target.options;
    //   const selectedOptions = [];
    //   const selectedValues = [];

    //   for (let i = 0; i < options.length; i++) {
    //     if (options[i].selected) {
    //       selectedOptions.push(options[i]);
    //       selectedValues.push(options[i].value);
    //     }
    //   }

    //   console.log(selectedOptions);
    //   console.log(selectedValues);
    // });


    var modalKelompok = document.getElementById('modalKelompok');
    var pinjam_id = document.getElementById('pinjam_id');
    var nama_kelompok = document.getElementById('nama_kelompok');
    var ketua_kelompok = document.getElementById('ketua_kelompok');
    var anggota_kelompok = document.getElementById('anggota_kelompok');

    function tambahkelompok() {
      var ak = [];
      $('input[name="anggota_kelompok[]"]');
      console.log(ak.length);
      // $.ajax({
      //   url: "{{ url('peminjam/kelompok') }}",
      //   type: "POST",
      //   data: {
      //     "_token": "{{ csrf_token() }}", 
      //     "pinjam_id": pinjam_id.value,
      //     "nama_kelompok": nama_kelompok.value,
      //     "ketua_kelompok": ketua_kelompok.value,
      //     "anggota_kelompok[]": anggota_kelompok.value,
      //   },
      //   dataType: "json",
      //   success: function(data) {
      //     console.log(data);
      //   },
      // });
    }

    var tbodykelompok = document.getElementById("tbodykelompok");

    function hapusKelompok(id) {
      $.ajax({
        url: "{{ url('peminjam/kelompok') }}" + "/" + id,
        type: "POST",
        data: {
          "_token": "{{ csrf_token() }}",
          "_method": "DELETE"
        },
        success: function(data) {
          tbodykelompok.removeChild(document.getElementById("rowkelompok" + id));
          if (data == 0) {
            var row = tbodykelompok.insertRow(0);
            var cell = row.insertCell(0);
            cell.colSpan = 5;
            cell.className = "text-center";
            cell.innerHTML = "- Kelompok belum ditambahkan -";
          }
        },
      });
    }

    var tanggalAwal = document.getElementById('tanggal_awal');
    var tanggalAkhir = document.getElementById('tanggal_akhir');
    var jamAwal = document.getElementById('jam_awal');
    var jamAkhir = document.getElementById('jam_ahir');
    var today = "{{ Carbon\Carbon::now()->format('Y-m-d') }}";
    tanggalAkhir.value = today;
    tanggalAkhir.min = today;
    tanggalAwal.addEventListener('change', function() {
      if (this.value != today) {
        tanggalAkhir.value = "";
      }
      tanggalAkhir.setAttribute('min', this.value);
    });

    var jumlahkelompok = document.getElementById('jumlahkelompok');
    var submitkelompok = document.getElementById('submitkelompok');
    var dataKelompok = document.getElementById('dataKelompok');
    var tambah = document.getElementById('tambah');
    var nama = document.getElementById('nama');
    var ketua = document.getElementById('ketua');
    var anggota = document.getElementById('anggota');

    // tambah.addEventListener('click', tambahkelompok());

    // submitkelompok.addEventListener('click', function () {
    //   $.ajax({
    //     url: "{{ url('peminjam/kelompok') }}",
    //     type: "POST",
    //     data: { "nama": nama.value, "ketua": ketua.value, "anggota": anggota.value },
    //     dataType: "json",
    //     success: function(data) {
    //       console.log("asas");
    //     },
    //   });
    // });

    // function tambahkelompok() {
    //   const namakelompok = document.createElement('input');
    // }

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
        console.log('ahhaha');
        $item = listItem;
        $no = 1;
        $detailpinjams = "{{ count($detailpinjams) }}";
        console.log($detailpinjams);
        $.ajax({
          url: "{{ url('peminjam/pilih') }}",
          type: "GET",
          data: {
            "items": $item
          },
          dataType: "json",
          success: function(data) {
            if (data != null) {
              if ($detailpinjams == 0) {
                $("#dataItems").empty();
              }
              $no = 1;
              $.each(data, function(key, value) {
                $("#dataItems").append("<tr>\
                                        <td class='text-center'>" + $no++ + "</td>\
                                        <td>" + value.nama + "</td>\
                                        <td>" + value.normal + " " + value.satuan.singkatan + "</td>\
                                        <td>\
                                          <div class='input-group'>\
                                            <input class='form-control' type='number' id='jumlahId' name='jumlah[" +
                  key +
                  "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= " +
                  value.normal + " ? Math.abs(this.value) : null' required>\
                                            <input type='hidden' name='barang_id[" + key + "]' value='" + value.id + "' class='form-control'>\
                                            <select class='custom-select' id='satuan" + key + "' name='satuan[" + key + "]'>\
                                              <option value='6'>Pcs</option>\
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
      9
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
    var vTaAw = document.getElementById('tanggal_awal');
    var vTaAk = document.getElementById('tanggal_akhir');
    var vJaAw = document.getElementById('jam_awal');
    var vJaAk = document.getElementById('jam_akhir');

    var form_pinjam = document.getElementById('form_pinjam');

    function submit() {
      form_pinjam.submit();
    }

    // var vKe = document.getElementById('keterangan');

    // function checkData() {
    //   if (vTaAw.value == "" || vTaAk.value == "" || vJaAw.value == "" || vJaAk.value == "" || count === 0) {
    //     swal("Warning", "Data tersimpan sebagai draft!, Lengkapi data untuk menyimpannya.", "peringatan");
    //   } else {
    //     $('#form-submit').submit();
    //   }
    // }
  </script>
@endsection
