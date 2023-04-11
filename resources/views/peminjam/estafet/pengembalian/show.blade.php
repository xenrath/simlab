@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('peminjam/estafet/pengembalian') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Peminjaman</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Peminjaman</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Waktu Praktik</strong>
                </div>
                <div class="col-md-8">
                  {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Ruang Lab</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->ruang->nama }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-4">
                  <strong>Laboran</strong>
                </div>
                <div class="col-md-8">
                  {{ $pinjam->ruang->laboran->nama }}
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
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Kelompok</h4>
        </div>
        <div class="card-body">
          <div class="row">
            @foreach ($pinjam->kelompoks as $kelompok)
            <div class="col-md-6 mb-3">
              <div class="border rounded p-3">
                <div class="row mb-3">
                  <div class="col-md-4">
                    <strong>Nama Kelompok</strong>
                  </div>
                  <div class="col-md-8">
                    {{ $kelompok->nama }}
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4">
                    <strong>Anggota</strong>
                  </div>
                  <div class="col-md-8">
                    <span class="bullet"></span>&nbsp;{{ $kelompok->m_ketua->nama }} (Ketua)<br>
                    @foreach ($kelompok->anggota as $anggota)
                      <span class="bullet"></span>&nbsp;{{ App\Models\User::where('kode', $anggota)->first()->nama }}
                      <br>
                    @endforeach
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <strong>Shift</strong>
                  </div>
                  <div class="col-md-8">
                    {{ $kelompok->shift }} ({{ $kelompok->jam }})
                  </div>
                </div>
              </div>
            </div>
          @endforeach
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h4>Alat</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Nama</th>
                  <th>Ruang Lab</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($detailpinjams as $detailpinjam)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $detailpinjam->barang->nama }}</td>
                    <td>{{ $detailpinjam->barang->ruang->nama }}</td>
                    <td>{{ $detailpinjam->jumlah }} {{ $detailpinjam->satuan->singkatan }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <form action="{{ url('peminjam/estafet/pengembalian/' . $pinjam->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
          <div class="card-header">
            <h4>Tambah Alat</h4>
          </div>
          <div class="card-body p-0">
            <div class="p-4">
              <a href="" class="btn btn-info float-right mb-3" data-toggle="modal" data-target="#modalBarang">
                Pilih
              </a>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th>Alat</th>
                    <th>Ruang</th>
                    <th>Stok</th>
                    <th>Jumlah</th>
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
            <textarea class="form-control" id="bahan" name="bahan" style="height: 120px">{{ old('bahan', $pinjam->bahan) }}</textarea>
          </div>
        </div>
        <button type="submit" class="btn btn-primary float-right">
          Simpan
        </button>
      </form>
    </div>
  </section>
  <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarang"
    aria-hidden="true">
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
            <button type="button" class="btn btn-warning mt-1 text-white" id="uncheckAll">Uncheck
              Semua</button>
            <button type="button" class="btn btn-primary mt-1 text-white ml-1" id="addItem">Masukan
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
  <script>
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
              $("#dataItems").empty();
              $no = 1;
              $.each(data, function(key, value) {
                $("#dataItems").append("<tr>\
                  <td class='text-center'>" + $no++ + "</td>\
                  <td>" + value.nama + "</td>\
                  <td>" + value.ruang.nama + "</td>\
                  <td>" + value.normal + " " + value.satuan.singkatan + "</td>\
                  <td>\
                      <input class='form-control' type='number' id='jumlahId' name='jumlah[" +
                  key +
                  "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= " +
                  value.normal + " ? Math.abs(this.value) : 0' value='1' required>\
                    <input type='hidden' name='barang_id[" + key + "]' value='" + value.id + "' class='form-control'>\
                  </td>\
                </tr>");
              });
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
  </script>
@endsection
