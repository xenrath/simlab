@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('laboran/kelompok/pengembalian') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Detail Pinjaman</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Detail Pinjaman</h4>
        </div>
        <div class="card-body">
          <div class="row p-0">
            <div class="col-md-6 p-0">
              <table class="table">
                <tr>
                  <th class="w-25">Nama Peminjam</th>
                  <td class="w-50">{{ $pinjam->peminjam->nama }}</td>
                </tr>
                <tr>
                  <th class="w-25">Waktu Pinjam</th>
                  <td class="w-50">{{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}</td>
                </tr>
                <tr>
                  <th class="w-25">Waktu Kembali</th>
                  <td class="w-50">{{ date('d M Y', strtotime($pinjam->tanggal_akhir)) }}</td>
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
                  <td class="w-50">{{ $pinjam->ruang->laboran->nama }}</td>
                </tr>
                <tr>
                  <th class="w-25">Keterangan</th>
                  <td class="w-50">
                    @if ($pinjam->keterangan)
                      {{ $pinjam->keterangan }}
                    @else
                      -
                    @endif
                  </td>
                </tr>
                <tr>
                  <th class="w-25">Mata Kuliah</th>
                  <td class="w-50">{{ $pinjam->matakuliah }}</td>
                </tr>
                <tr>
                  <th class="w-25">Dosen</th>
                  <td class="w-50">{{ $pinjam->dosen }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Detail Kelompok</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama</th>
                  <th>Ketua</th>
                  <th>Anggota</th>
                  <th>Waktu</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($kelompoks as $kelompok)
                  <tr>
                    <td class="text-center align-top py-1">{{ $loop->iteration }}</td>
                    <td class="align-top py-1">{{ $kelompok->nama }}</td>
                    <td class="align-top py-1">{{ $kelompok->m_ketua->nama }}</td>
                    <td class="py-1">
                      @foreach ($kelompok->anggota as $anggota)
                        <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                        <br>
                      @endforeach
                    </td>
                    <td class="align-top py-1">
                      {{ $kelompok->shift }}
                      ({{ $kelompok->jam }})</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <form action="{{ url('laboran/kelompok/pengembalian/' . $pinjam->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
          <div class="card-header">
            <h4>Daftar Barang</h4>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <a href="" class="btn btn-info float-right mb-3" data-toggle="modal" data-target="#modalBarang">
                <i class="far fa-check-square"></i>&nbsp; Pilih Barang
              </a>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Nama</th>
                    <th>Ruang</th>
                    <th>Stok Barang</th>
                    <th>Jumlah Pinjam</th>
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
            <h4>Daftar Bahan</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="bahan">Bahan-bahan</label>
              <textarea class="form-control" id="bahan" name="bahan" style="height: 120px">{{ old('bahan', $pinjam->bahan) }}</textarea>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary float-right">
          <i class="fa fa-save"></i>&nbsp; Simpan
        </button>
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
        console.log('ahhaha');
        $item = listItem;
        $no = 1;
        $detailpinjams = "{{ count($detailpinjams) }}";
        console.log($detailpinjams);
        $.ajax({
          url: "{{ url('laboran/pilih') }}",
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
                    <td>" + value.ruang.nama + "</td>\
                    <td>" + value.normal + " " + value.satuan.singkatan + "</td>\
                    <td>\
                      <div class='input-group'>\
                        <input class='form-control' type='number' id='jumlahId' name='jumlah[" + key +
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
