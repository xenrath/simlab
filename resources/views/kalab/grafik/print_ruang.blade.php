<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Peminjaman Ruang</title>
    <style>
        body {
            padding: 0px;
            font-size: 12px;
            line-height: 1.4;
        }

        .logo {
            width: 60px;
            position: absolute;
        }

        .header {
            margin-left: 70px;
            height: 60px;
        }

        .header .h1 {
            font-size: 24px;
            font-weight: bold;
            display: block;
        }

        .header .h2 {
            font-size: 14px;
            font-weight: bold;
            display: block;
        }

        .header .p {
            display: block;
        }

        .table-1 .td-1,
        .th-1 {
            border: 1px solid black;
            padding: 6px;
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
    <img src="{{ asset('storage/uploads/logo-bhamada-sm.png') }}" alt="Bhamada" class="logo">
    <div class="header">
        <span class="h2">UNIVERSITAS BHAMADA SLAWI</span>
        <span class="p">Kampus : Jl. Cut Nyak Dhien No. 16, Kalisapu, Slawi, Kab. Tegal</span>
        <span class="p">Telp. (0283)6197570, 6197571</span>
    </div>
    <hr>
    <br>
    <div style="text-align: center">
        <span style="font-size: 14px; font-weight: bold; display: block;">
            REKAP PEMINJAMAN RUANG
        </span>
    </div>
    <br>
    <table style="width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="3">
                <strong>Berdasarkan</strong>
            </td>
        </tr>
        <tr>
            <td style="width: 60px;">Prodi</td>
            <td style="width: 10px; text-align: center;">:</td>
            <td>
                {{ Request::get('prodi_id') ? (Request::get('prodi_id') != '5' ? 'Prodi' : '') : '' }}
                {{ Request::get('prodi_id') ? ucfirst($prodi) : 'Semua' }}
            </td>
        </tr>
        <tr>
            <td style="width: 60px;">Tahun</td>
            <td style="width: 10px; text-align: center;">:</td>
            <td>
                {{ Request::get('tahun') ?? 'Semua' }}
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;" class="table-1" cellspacing="0" cellpadding="0">
        <tr>
            <td class="td-1" width="10px">No</td>
            <td class="td-1">Nama Ruang</td>
            <td class="td-1" style="width: 140px">Jumlah Pemakaian</td>
        </tr>
        @foreach ($labels as $key => $label)
            <tr>
                <td class="td-1" style="text-align: center">{{ $loop->iteration }}</td>
                <td class="td-1">{{ $label }}</td>
                <td class="td-1">{{ $data[$key] }} kali</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
