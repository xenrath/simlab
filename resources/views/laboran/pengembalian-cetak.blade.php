<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nota Peminjaman</title>
  <style>
    /* table tr td,
    table tr th {
      font-size: 12pt;
    } */
    .table-1 .td-1,
    .th-1 {
      border: 1px solid black;
      padding: 8px
    }

    @font-face {
      font-family: 'Vibes';
      src: url("https://fonts.googleapis.com/css2?family=Roboto") format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    * {
      font-family: 'Roboto', sans-serif;
      /* border: 1px solid black; */
    }

    .text-center {
      text-align: center
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
        <img src="{{ asset('storage/uploads/logo-bhamada.png') }}" alt="Bhamada" width="80px">
      </td>
      <td class="text-header">
        LABORATORIUM KESEHATAN
        <br>
        UNIVERSITAS BHAKTI MANDALA HUSADA SLAWI
      </td>
    </tr>
  </table>
  <br>
  <hr>
  <h3 style="text-align: center">
    <u>NOTA PEMINJAMAN ALAT / BARANG</u>
  </h3>
  <br>
  <span style="float: left;">{{ $pinjam->ruang->nama }}</span>
  <span style="float: right;">{{ Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
  <br>
  <br>
  <h4>Detail Pinjaman : </h4>
  <table cellspacing="0" class="table-1" style="width: 100%">
    <tr>
      <td class="td-1">Nama Peminjam</td>
      <td class="td-1" style="text-align: center">:</td>
      <td class="td-1">{{ $pinjam->peminjam->nama }}</td>
    </tr>
    <tr>
      <td class="td-1">Tanggal Pinjam</td>
      <td class="td-1" style="text-align: center">:</td>
      <td class="td-1">{{ date('G:i, d-m-Y', strtotime($pinjam->tanggal_awal . $pinjam->jam_awal)) }}</td>
    </tr>
    <tr>
      <td class="td-1">Tanggal Kembali</td>
      <td class="td-1" style="text-align: center">:</td>
      <td class="td-1">{{ date('G:i, d-m-Y', strtotime($pinjam->tanggal_akhir . $pinjam->jam_akhir)) }}</td>
    </tr>
    <tr>
      <td class="td-1">Keterangan</td>
      <td class="td-1" style="text-align: center">:</td>
      <td class="td-1">
        @if ($pinjam->keterangan == "")
        -
        @else
        {{ $pinjam->keterangan }}
        @endif
      </td>
    </tr>
  </table>
  <p style="font-weight: bold">Catatan,</p>
  <p>Kerusakan / kehilangan alat menjadi tanggung jawab Peminjam.</p>
  <br>
  <p style="text-align: center">Menyetujui Peminjaman,</p>
  <table style="width: 100%;">
    <tr>
      <td style="text-align: center">Laboran</td>
      <td style="text-align: center">Peminjam</td>
    </tr>
    <br>
    <br>
    <br>
    <br>
    <tr>
      <td style="text-align: center">({{ $pinjam->laboran->nama }})</td>
      <td style="text-align: center">({{ $pinjam->peminjam->nama }})</td>
    </tr>
  </table>
  <br>
  <hr>
  <br>
  <p style="text-align: center">Menyetujui Pengembalian,</p>
  <table style="width: 100%;">
    <tr>
      <td style="text-align: center">Laboran</td>
      <td style="text-align: center">Peminjam</td>
    </tr>
    <br>
    <br>
    <br>
    <br>
    <tr>
      <td style="text-align: center">({{ $pinjam->laboran->nama }})</td>
      <td style="text-align: center">({{ $pinjam->peminjam->nama }})</td>
    </tr>
  </table>
  <div style="page-break-after: always"></div>
  @if (count($barangs) > 0)
  <h4>Barang : </h4>
  <table cellspacing="0" class="table-1" style="width: 100%">
    <tr>
      <th class="th-1">No.</th>
      <th class="th-1" style="text-align: left">Nama Barang</th>
      <th class="th-1" style="text-align: left">Nomor Inventaris</th>
      <th class="th-1">Jumlah</th>
    </tr>
    @foreach ($barangs as $barang)
    <tr>
      <td class="td-1" style="text-align: center">{{ $loop->iteration }}.</td>
      <td class="td-1">{{ $barang->barang->nama }}</td>
      <td class="td-1">{{ $barang->barang->kode }}</td>
      <td class="td-1" style="text-align: center">{{ $barang->jumlah }}</td>
    </tr>
    @endforeach
  </table>
  @endif
  @if (count($bahans) > 0)
  <h4>Bahan : </h4>
  <table cellspacing="0" class="table-1" style="width: 100%">
    <tr>
      <th class="th-1">No.</th>
      <th class="th-1" style="text-align: left">Nama Bahan</th>
      <th class="th-1" style="text-align: left">Nomor Inventaris</th>
      <th class="th-1">Jumlah</th>
    </tr>
    @foreach ($bahans as $bahan)
    <tr>
      <td class="td-1" style="text-align: center">{{ $loop->iteration }}.</td>
      <td class="td-1">{{ $bahan->barang->nama }}</td>
      <td class="td-1">{{ $bahan->barang->kode }}</td>
      <td class="td-1" style="text-align: center">{{ $bahan->jumlah }}</td>
    </tr>
    @endforeach
  </table>
  @endif
</body>
</html>