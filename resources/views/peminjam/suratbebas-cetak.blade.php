<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Nota Peminjaman</title>
  <style>
    body {
      padding: 0px 24px
    }
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
    <u>SURAT KETERANGAN BEBAS LABORATORIUM</u>
  </h3>
  <br>
  <p>Dengan ini menerangkan bahwa : </p>
  <table style="width: 100%;" class="table-1" cellspacing="0" cellpadding="10">
    <tr>
      <td class="td-1" width="100px">Nama</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $user->nama }}</td>
    </tr>
    <tr>
      <td class="td-1" width="100px">NIM</td>
      <td class="td-1" width="20px" style="text-align: center">:</td>
      <td class="td-1">{{ $user->kode }}</td>
    </tr>
  </table>
  <p>Telah menyelesaikan semua Administrasi Laboratorium Program Studi {{ $user->prodi->jenjang }} {{ $user->prodi->nama
    }} Universitas
    Bhamada Slawi.</p>
  <table style="width: 100%;" cellspacing="0" cellpadding="8">
    <tr>
      <td></td>
      <td></td>
      <td style="text-align: center; width: 240px">
        <p>Slawi, {{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Kepala Laboran
          {{-- {{ $user->prodi->jenjang }} 
          @if ($user->prodi->nama == "Keselamatan dan Kesehatan Kerja")
          K3
          @else
          {{ $user->prodi->nama }}</p>
          @endif --}}
        <br>
        <br>
        <br>
        <br>
        {{ $kalab->nama }}
        <hr>
      </td>
    </tr>
  </table>
</body>
</html>