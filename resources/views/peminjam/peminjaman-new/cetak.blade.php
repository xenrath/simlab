<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nota Peminjaman</title>
  <style>
    .table-1 .td-1,
    .th-1 {
      border: 1px solid black;
    }

    .td-1 {
      text-align: left;
    }

    * {
      font-family: 'Times New Roman', Times, serif;
      /* border: 1px solid black; */
    }

    .text-center {
      text-align: center
    }

    .text-left {
      text-align: left
    }

    .text-header {
      font-size: 18px;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <table width="100%">
    <tr>
      <td>
        <img src="{{ asset('storage/uploads/logo-bhamada1.png') }}" alt="Bhamada" width="100px">
      </td>
      <td style="text-align: center; letter-spacing: 1px">
        <span style="font-weight: bold; font-size: 20px;">UNIVERSITAS BHAMADA SLAWI</span>
        <br>
        <span style="font-weight: bold; font-size: 18px;">UNIT LABORATORIUM KESEHATAN</span>
        <br>
        <span>Alamat : Jl. Cut Nyak Dhien No. 16, Kalisapu, Slawi - Kab. Tegal</span>
        <br>
        <span>Telp. (0283)6197570, 6197571 Fax. (0283)6198450</span>
      </td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>
  <hr>
  <br>
  <h3 style="text-align: center">
    <u>DAFTAR PEMINJAMAN BARANG</u>
  </h3>
  <br>
  <p>Dengan ini menerangkan bahwa : </p>
  <table style="width: 100%;" class="table-1" cellspacing="0" cellpadding="8">
    <tr>
      <td class="td-1" width="160px">Nama</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->peminjam->nama }}</td>
    </tr>
    <tr>
      <td class="td-1" width="160px">NIM</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->peminjam->kode }}</td>
    </tr>
    <tr>
      <td class="td-1" width="160px">Prodi</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->peminjam->subprodi->jenjang }} {{ $pinjam->peminjam->subprodi->nama }}</td>
    </tr>
    {{-- <tr>
      <td class="td-1" width="160px">Semester</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->peminjam->semester }}</td>
    </tr> --}}
  </table>
  <p>Menggunakan fasilitas dari Unit Laboratorium Kesehatan Universitas Bhamada Slawi seperti : </p>
  <table style="width: 100%;" class="table-1" cellspacing="0" cellpadding="8">
    <tr>
      <td class="td-1" width="160px">Ruang Lab.</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->ruang->nama }}</td>
    </tr>
    <tr>
      <td class="td-1" width="160px">Waktu Peminjaman</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      @php
      $tanggal_awal = date('d/m/Y', strtotime($pinjam->tanggal_awal));
      $tanggal_akhir = date('d/m/Y', strtotime($pinjam->tanggal_akhir));
      $jam_awal = $pinjam->jam_awal;
      $jam_akhir = $pinjam->jam_akhir;
      $now = Carbon\Carbon::now();
      $expire = date('Y-m-d h:i:s', strtotime($pinjam->tanggal_akhir . $jam_akhir));
      @endphp
      <td class="td-1">
        @if ($tanggal_awal == $tanggal_akhir)
        {{ $jam_awal }} - {{ $jam_akhir }}, {{ $tanggal_awal }}
        @else
        {{ $jam_awal }}, {{ $tanggal_awal }} <br> {{ $jam_akhir }}, {{ $tanggal_akhir }}
        @endif
      </td>
    </tr>
    <tr>
      <td class="td-1" width="160px">Mata Kuliah</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->matakuliah }}</td>
    </tr>
    <tr>
      <td class="td-1" width="160px">Dosen Pengampu</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->dosen }}</td>
    </tr>
    <tr>
      <td class="td-1" width="160px">Keterangan</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->keterangan }}</td>
    </tr>
  </table>
  <p>
    <strong>Catatan : </strong>
  </p>
  <p>Kerusakan / kehilangan barang menjadi tanggung jawab Peminjam.</p>
  <div style="position: absolute; bottom: 0; width: 100%;">
    <p style="text-align: center;">Menyetujui Peminjaman,</p>
    <br>
    <table style="width: 100%;" cellpadding="0">
      <tr>
        <td style="text-align: center; width: 100%">Laboran</td>
        <td style="text-align: center; width: 100%">Peminjam</td>
      </tr>
      <br>
      <br>
      <br>
      <br>
      <br>
      <tr>
        <td style="text-align: center">({{ $pinjam->ruang->laboran->nama }})</td>
        <td style="text-align: center">({{ $pinjam->peminjam->nama }})</td>
      </tr>
    </table>
  </div>
  <div style="page-break-after: always"></div>
  <p>
    <strong>Daftar barang yang dipinjam :</strong>
  </p>
  <br>
  @if (count($barangs) > 0)
  <table style="width: 100%;" class="table-1" cellspacing="0" cellpadding="8">
    <tr>
      <th class="th-1">No.</th>
      <th class="th-1 text-left">Nomor Inventaris</th>
      <th class="th-1 text-left">Nama Barang</th>
      <th class="th-1">Jumlah</th>
    </tr>
    @foreach ($barangs as $barang)
    <tr>
      <td class="td-1 text-center">{{ $loop->iteration }}</td>
      <td class="td-1">{{ $barang->barang->kode }}</td>
      <td class="td-1">{{ $barang->barang->nama }}</td>
      <td class="td-1 text-center">{{ $barang->jumlah }} {{ ucfirst($barang->barang->satuan->nama) }}</td>
    </tr>
    @endforeach
  </table>
  @endif
</body>
</html>