<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Peminjaman Barang</title>
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
                <img src="{{ asset('storage/uploads/logo-bhamada-sm.png') }}" alt="Bhamada" width="100px">
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
        <u>Rekap Peminjaman Barang</u>
    </h3>
    <br>
    <p>
        <strong>Filter</strong>
        <br>
        Prodi : {{ ucfirst($prodi ?? '-') }}, Peminjam : {{ ucfirst($peminjam ?? '-') }}
    </p>
    <table style="width: 100%;" class="table-1" cellspacing="0" cellpadding="10">
        <tr>
            <td class="td-1" width="10px">No</td>
            <td class="td-1">Nama Barang</td>
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
