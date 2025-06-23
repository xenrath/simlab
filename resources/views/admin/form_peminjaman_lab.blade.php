<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Peminjaman Laboratorium</title>
    <style>
        @page {
            size: 21cm 33cm;
            /* F4: Lebar x Tinggi */
            margin: 1cm;
            /* Bebas kamu atur */
        }

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

        .kode {
            position: absolute;
            right: 0;
            top: 0;
        }

        .hr {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .tanggal {
            display: flex;
            float: right;
        }

        .table {
            width: 100%;
        }

        .table .th {
            border: 1px solid black;
            text-align: left;
            padding: 6px;
            vertical-align: top;
        }

        .table .td {
            border: 1px solid black;
            text-align: left;
            padding: 6px;
            vertical-align: top;
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

        .layout-ttd {
            display: inline-flex;
            text-align: center;
        }

        .text-muted {
            font-size: 14px;
            opacity: 80%;
        }

        .page-break {
            page-break-after: always;
        }

        .border {
            border: 1px solid black;
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
    <div class="kode">
        <table class="table" cellspacing="0" cellpadding="0">
            <tr>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">Kode</td>
                <td class="td" style="font-weight: bold; text-align: center; font-size: 12px; padding: 2px 4px;">:
                </td>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">
                    F.SPMI.BMD/LAB.KESH.02/2025
                </td>
            </tr>
            <tr>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">Tanggal</td>
                <td class="td" style="font-weight: bold; text-align: center; font-size: 12px; padding: 2px 4px;">:
                </td>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">1 Mei 2025</td>
            </tr>
            <tr>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">Revisi</td>
                <td class="td" style="font-weight: bold; text-align: center; font-size: 12px; padding: 2px 4px;">:
                </td>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">-</td>
            </tr>
        </table>
    </div>
    <br><br>
    <div style="text-align: center">
        <span style="font-size: 14px; font-weight: bold; display: block;">FORM PEMINJAMAN LABORATORIUM</span>
    </div>
    <br>
    <table class="table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="td" colspan="4">
                Kategori Peminjaman <span style="opacity: 0.8">(*Centang salah satu)</span>
                <span style="float: right;">
                    <img src="{{ asset('storage/uploads/asset/square-regular.svg') }}"
                        style="height: 16px; vertical-align: middle; margin-right: 2px;">
                    Laboratorium
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <img src="{{ asset('storage/uploads/asset/square-regular.svg') }}"
                        style="height: 16px; vertical-align: middle; margin-right: 2px;">
                    Dalam Kelas
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <img src="{{ asset('storage/uploads/asset/square-regular.svg') }}"
                        style="height: 16px; vertical-align: middle; margin-right: 2px;">
                    PKL
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <img src="{{ asset('storage/uploads/asset/square-regular.svg') }}"
                        style="height: 16px; vertical-align: middle; margin-right: 2px;">
                    Pinjam Ruang
                </span>
            </td>
        </tr>
        <tr>
            <td class="td" style="width: 100px;">Nama Peminjam</td>
            <td class="td">&nbsp;</td>
            <td class="td" style="width: 100px;">Tanggal Pinjam</td>
            <td class="td">&nbsp;</td>
        </tr>
        <tr>
            <td class="td" style="width: 100px;">NIM</td>
            <td class="td">&nbsp;</td>
            <td class="td" style="width: 100px;">Ruang Lab</td>
            <td class="td">&nbsp;</td>
        </tr>
        <tr>
            <td class="td" style="width: 100px;">Prodi</td>
            <td class="td">&nbsp;</td>
            <td class="td" style="width: 100px;">Mata Kuliah</td>
            <td class="td">&nbsp;</td>
        </tr>
        <tr>
            <td class="td" style="width: 100px;">Semester</td>
            <td class="td">&nbsp;</td>
            <td class="td" style="width: 100px;">Praktik</td>
            <td class="td">&nbsp;</td>
        </tr>
    </table>
    <br>
    <table class="table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="td" style="width: 20px; font-weight: bold; text-align: center;">No</td>
            <td class="td" style="font-weight: bold; text-align: center;">Nama Alat / Barang</td>
            <td class="td" style="width: 80px; font-weight: bold; text-align: center;">Jumlah</td>
            <td class="td" style="width: 80px; font-weight: bold; text-align: center;">Keterangan</td>
        </tr>
        @for ($i = 1; $i <= 20; $i++)
            <tr>
                <td class="td" style="text-align: center;">{{ $i }}</td>
                <td class="td">&nbsp;</td>
                <td class="td">&nbsp;</td>
                <td class="td">&nbsp;</td>
            </tr>
        @endfor
    </table>
    <br>
    <table style="width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 50%;">
                <div class="layout-ttd">
                    <p class="ttd-p">
                        <span>Menyetujui,</span>
                        <br>
                        <span>Laboran Penerima</span>
                    </p>
                    <br><br>
                    <span>____________________</span>
                </div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="layout-ttd">
                    <p class="ttd-p">
                        <span>&nbsp;</span>
                        <br>
                        Peminjam
                    </p>
                    <br><br>
                    <span>____________________</span>
                </div>
            </td>
        </tr>
    </table>
    <small style="position: absolute; bottom: 0;">
        <strong>* note :</strong>
        <span>kerusakan/kehilangan alat menjadi tanggung jawab peminjam.</span>
    </small>
</body>

</html>
