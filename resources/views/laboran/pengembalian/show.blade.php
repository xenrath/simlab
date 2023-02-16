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
          <h4>Detail Peminjaman</h4>
          <div class="card-header-action">
            @php
              $tanggal_awal = date('d/m/Y', strtotime($pinjam->tanggal_awal));
              $tanggal_akhir = date('d/m/Y', strtotime($pinjam->tanggal_akhir));
              $jam_awal = $pinjam->jam_awal;
              $jam_akhir = $pinjam->jam_akhir;
              $now = Carbon\Carbon::now();
              $expire = date('Y-m-d G:i:s', strtotime($pinjam->tanggal_awal . $jam_awal));
            @endphp
            @if ($now > $expire)
              <span class="badge badge-danger">Kadaluarsa</span>
            @else
              <span class="badge badge-warning">Menunggu</span>
            @endif
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              @if (!$pinjam->kelompoks->first()->anggota)
                <div class="row mb-3">
                  <div class="col-md-4">
                    <strong>Peminjam</strong>
                  </div>
                  <div class="col-md-8">
                    {{ $pinjam->peminjam->nama }}
                  </div>
                </div>
              @endif
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Waktu Pinjam</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->jam_awal }}, {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Waktu Kembali</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->jam_akhir }}, {{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Ruang Lab.</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->ruang->nama }}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Mata Kuliah</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->matakuliah }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Dosen</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->dosen }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Keterangan</strong>
                </div>
                <div class="col-md-8">
                  @if ($pinjam->keterangan)
                    {{ $pinjam->keterangan }}
                  @else
                    -
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @if ($pinjam->kelompoks->first()->anggota)
        <div class="card">
          <div class="card-header">
            <h4>Detail Kelompok</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="row mb-3">
                  <div class="col-md-4">
                    <strong>Ketua</strong>
                  </div>
                  <div class="col-md-8">
                    {{ $pinjam->kelompoks->first()->m_ketua->nama }}
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4">
                    <strong>Anggota</strong>
                  </div>
                  <div class="col-md-8">
                    @php
                      $kelompok = $pinjam->kelompoks->first();
                    @endphp
                    @foreach ($kelompok->anggota as $anggota)
                      <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                      <br>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
      <div class="card">
        <div class="card-header">
          <h4>Detail Bahan</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-2">
              <label for="bahan">
                <strong>Bahan</strong>
              </label>
            </div>
            <div class="col-md-10">
              <textarea name="bahan" id="bahan" cols="30" rows="10" class="form-control" style="height: 100px;">{{ old('bahan', $pinjam->bahan) }}</textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Detail Alat</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama Alat</th>
                  <th>Ruang</th>
                  <th class="text-center">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($detailpinjams as $detailpinjam)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $detailpinjam->barang->nama }}</td>
                    <td>{{ $detailpinjam->barang->ruang->nama }}</td>
                    <td class="text-center">{{ $detailpinjam->jumlah }} {{ ucfirst($detailpinjam->satuan->nama) }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <form action="{{ url('laboran/pengembalian/' . $pinjam->id . '/update') }}" method="POST" id="form-submit">
        @csrf
        <div class="card mt-3">
          <div class="card-header">
            <h4>Tambah Pinjaman</h4>
          </div>
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
        </div>
        <div class="text-right">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>&nbsp;Update
          </button>
        </div>
      </form>
    </div>
  </section>
  <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarang" aria-hidden="true">
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
            <button type="button" class="btn btn-warning text-white mr-1" id="uncheckAll">Uncheck
              Semua</button>
            <button type="button" class="btn btn-primary text-white" id="addItem">Masukan
              Barang</button>
          </div>
          <table class="table table-hover" id="table-1">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th>Alat</th>
                <th>Ruang</th>
                <th>Stok</th>
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
                  <td>{{ $barang->normal }} {{ $barang->satuan->singkatan }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
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
        $.ajax({
          url: "{{ url('laboran/pilih') }}",
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
                        <td>" + value.nama + " (" + value.kategori + ")</td>\
                        <td>" + value.normal + " " + value.satuan.singkatan + "</td>\
                        <td>\
                          <div class='input-group'>\
                            <input class='form-control' type='number' id='jumlahId' name='jumlah[" + key +
                  "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= " +
                  value.normal + " ? Math.abs(this.value) : null' value='1' required>\
                            <input type='hidden' name='barang_id[" + key + "]' value='" + value.id + "' class='form-control'>\
                            <select class='custom-select' id='satuan" + key + "' name='satuan[" + key + "]'>\
                              <option value=" + value.satuan.id + ">" + value.satuan.singkatan + "</option>\
                            </select>\
                          </div>\
                        </td>\
                      </tr>");
                if (value.kategori == "bahan") {
                  if (value.satuan.kategori == "volume") {
                    $("#satuan" + key + "").append("<option value='1'>L</option>\
                          <option value='2'>mL</option>");
                    $("#satuan" + key + "").val(value.satuan_id).attr('selected', true);
                  } else {
                    $("#satuan" + key + "").append("<option value='3'>Kg</option>\
                          <option value='4'>g</option>\
                          <option value='5'>mg</option>");
                    $("#satuan" + key + "").val(value.satuan_id).attr('selected', true);
                  }
                } else {
                  $("#satuan" + key + "").append("");
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
      document.getElementById("countChecked").textContent = listItem.length;
      $item = listItem;
      $.ajax({
        url: "{{ url('laboran/pilih') }}",
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
  </script>
@endsection
