@extends('layouts.app')

@section('title', 'Buat Peminjaman')

@section('content')
  <form action="{{ url('peminjam/estafet/peminjaman/' . $pinjam->id) }}" method="POST" autocomplete="off" id="form-submit">
    @csrf
    @method('put')
    <section class="section">
      <div class="section-header">
        <div class="section-header-back">
          <a href="{{ url('peminjam/estafet/peminjaman') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
          </a>
        </div>
        <h1>Buat Peminjaman</h1>
      </div>
      @if (session('error_peminjaman') || session('empty_kelompok') || session('error_kelompok') || session('empty_barang'))
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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Peminjaman</h4>
              </div>
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="waktu">Waktu Pemakaian</label>
                  <select class="form-control selectric" id="waktu" name="waktu">
                    <option value="0" {{ old('waktu') == '0' ? 'selected' : '' }}>Hari ini</option>
                    <option value="1" {{ old('waktu', '1') == '1' ? 'selected' : '' }}>Besok</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="matakuliah">Mata Kuliah</label>
                  <input type="text" name="matakuliah" id="matakuliah" class="form-control"
                    value="{{ old('matakuliah', $pinjam->matakuliah) }}">
                </div>
                <div class="form-group">
                  <label for="dosen">Dosen Pengampu</label>
                  <input type="text" name="dosen" id="dosen" class="form-control"
                    value="{{ old('dosen', $pinjam->dosen) }}">
                </div>
                <div class="form-group">
                  <label for="ruang_id">Ruang Lab.</label>
                  <select class="form-control selectric" id="ruang_id" name="ruang_id">
                    <option value="">- Pilih Ruang -</option>
                    @forelse ($ruangs as $ruang)
                      <option value="{{ $ruang->id }}"
                        {{ old('ruang_id', $pinjam->ruang_id) == $ruang->id ? 'selected' : '' }}>
                        {{ $ruang->nama }}</option>
                    @empty
                      <option value="" class="text-center" disabled>Ruang / Lab. tidak ditemukan</option>
                    @endforelse
                  </select>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>Kelompok</h4>
              </div>
              <div class="card-body p-0">
                <div class="p-4">
                  <button type="button" class="btn btn-info float-right mb-3"
                    data-toggle="modal" data-target="#modalKelompok">
                    Tambah
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
                            {{ $kelompok->shift }} <br> ({{ $kelompok->jam }})
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
            <div class="card">
              <div class="card-header">
                <h4>Barang</h4>
              </div>
              <div class="card-body p-0">
                <div class="p-4">
                  <a href="" class="btn btn-info float-right mb-3" data-toggle="modal" data-target="#modalBarang">
                    Pilih Barang
                  </a>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center" style="width: 20px">No.</th>
                        <th>Nama Barang</th>
                        <th>Ruang Lab</th>
                        <th>Stok</th>
                        <th>Jumlah</th>
                      </tr>
                    </thead>
                    <tbody id="dataItems">
                      @forelse ($detailpinjams as $detailpinjam)
                        <tr>
                          <td class="text-center">{{ $loop->iteration }}</td>
                          <td>{{ $detailpinjam->barang->nama }}</td>
                          <td>{{ $detailpinjam->barang->ruang->nama }}</td>
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
            <div class="card">
              <div class="card-header">
                <h4>Bahan</h4>
              </div>
              <div class="card-body">
                <textarea class="form-control" id="bahan" name="bahan" style="height: 120px"
                  placeholder="Masukan bahan yang dibutuhkan">{{ old('bahan', $pinjam->bahan) }}</textarea>
              </div>
            </div>
            <div class="float-right">
              <button type="button" class="btn btn-primary" onclick="form_submit('false')">Buat Pinjaman</button>
              {{-- <button type="button" class="btn btn-primary" onclick="submit()">
                Simpan
              </button> --}}
            </div>
          </div>
        </div>
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
              <button type="button" class="btn btn-warning mt-1 text-white" id="uncheckAll">Uncheck
                Semua</button>
              <button type="button" class="btn btn-primary mt-1 text-white ml-1" id="addItem">Masukan
                Barang</button>
            </div>
            <table class="table table-hover" id="table-1">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Barang</th>
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
                      {{-- <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="checkboxId" value="{{ $barang->id }}">
                    </div> --}}
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
    <div class="modal fade" id="modalKelompok" role="dialog" aria-labelledby="modalKelompok" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="font-weight-bold">Kelompok</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
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
                  <option value="{{ $peminjam->kode }}"
                    {{ $peminjam->kode == auth()->user()->kode ? 'selected' : '' }}>
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
                <option value="Shift 4">Shift 4</option>
              </select>
            </div>
            <div class="form-group">
              <label for="jam">Jam</label>
              <input type="time" class="form-control" name="jam" id="jam">
            </div>
            <input type="hidden" class="form-control" name="kelompok" id="kelompok" value="true">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="form_submit('true')">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </form>
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

    addItem.addEventListener('click', function() {
      if (listItem.length === 0) {
        alert("Pilih barang terlebih dahulu!");
      } else {
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
              $.each(data, function(key, barang) {
                var value = "1";
                if (item != null) {
                  for (let i = 0; i < jumlah.length; i++) {
                    const element = jumlah[i];
                    if (element['barang_id'] == barang.id) {
                      value = element['jumlah'];
                    }
                  }
                }
                $("#dataItems").append("<tr>\
                  <td class='text-center'>" + $no++ + "</td>\
                  <td>" + barang.nama + "</td>\
                  <td>" + barang.ruang.nama + "</td>\
                  <td>" + barang.normal + " " + barang.satuan.singkatan + "</td>\
                  <td>\
                    <div class='input-group'>\
                      <input class='form-control' type='number' id='jumlahId' name='jumlah[" + key + "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= " + barang.normal + " ? Math.abs(this.value) : null' value=" + value + " required>\
                      <input type='hidden' name='barang_id[" + key + "]' value='" + barang.id + "' class='form-control'>\
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
                <td colspan='5' class='text-center'>- Belum ada barang yang dipilih -</td>\
              </tr>");
          }
        },
      });
    });

    var kelompok = document.getElementById('kelompok');
    var matakuliah = document.getElementById('matakuliah');
    var dosen = document.getElementById('dosen');
    var ruang = document.getElementById('ruang');

    function form_submit(is_kelompok) {
      kelompok.value = is_kelompok;
      document.getElementById('form-submit').submit();
    }
  </script>
@endsection
