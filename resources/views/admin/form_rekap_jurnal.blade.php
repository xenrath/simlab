<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Rekap Jurnal</title>
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

        .header .h2 {
            font-size: 14px;
            font-weight: bold;
            display: block;
        }

        .header .p {
            font-size: 12px;
            display: block;
        }

        .kode {
            position: absolute;
            right: 0;
            top: 0;
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
                    F.SPMI.BMD/LAB.KESH.01/2021
                </td>
            </tr>
            <tr>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">Tanggal</td>
                <td class="td" style="font-weight: bold; text-align: center; font-size: 12px; padding: 2px 4px;">:
                </td>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">1 September 2021</td>
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
        <span style="font-size: 14px; font-weight: bold; display: block;">
            PENCATATAN AKTIVITAS DOSEN MEMBIMBING PRAKTIKUM
        </span>
        <span style="font-size: 14px; font-weight: bold; display: block;">UNIVERSITAS BHAMADA SLAWI</span>
        <span style="font-size: 14px; font-weight: bold; display: block;">PRODI ____________________</span>
    </div>
    <br>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td class="td" style="width: 120px; font-size: 12px; padding: 2px;">Mata Kuliah</td>
            <td class="td" style="width: 10px; font-size: 12px; text-align: center; padding: 2px;">:</td>
            <td class="td" style="font-size: 12px; padding: 2px;"></td>
        </tr>
        <tr>
            <td class="td" style="width: 120px; font-size: 12px; padding: 2px;">Tingkat / Semester</td>
            <td class="td" style="width: 10px; font-size: 12px; text-align: center; padding: 2px;">:</td>
            <td class="td" style="font-size: 12px; padding: 2px;"></td>
        </tr>
        <tr>
            <td class="td" style="width: 120px; font-size: 12px; padding: 2px;">Tahun Akademik</td>
            <td class="td" style="width: 10px; font-size: 12px; text-align: center; padding: 2px;">:</td>
            <td class="td" style="font-size: 12px; padding: 2px;"></td>
        </tr>
    </table>
    <br>
    <table class="table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="td"
                style="width: 160px; font-weight: bold; text-align: center; vertical-align: middle; padding: 2px;">
                Nama Dosen
            </td>
            <td class="td"
                style="width: 140px; font-weight: bold; text-align: center; vertical-align: middle; padding: 2px;">
                Hari, Tanggal
            </td>
            <td class="td"
                style="width: 80px; font-weight: bold; text-align: center; vertical-align: middle; padding: 2px;">
                Jam
            </td>
            <td class="td" style="font-weight: bold; text-align: center; vertical-align: middle; padding: 2px;">
                Perasat
            </td>
            <td class="td"
                style="width: 40px; font-weight: bold; text-align: center; vertical-align: middle; padding: 2px;">
                Jml<br>Mhs
            </td>
            <td class="td"
                style="width: 80px; font-weight: bold; text-align: center; vertical-align: middle; padding: 2px;">
                Paraf<br>Koord MK
            </td>
        </tr>
        @for ($i = 1; $i <= 35; $i++)
            <tr>
                <td class="td" style="padding: 2px;">&nbsp;</td>
                <td class="td" style="padding: 2px;">&nbsp;</td>
                <td class="td" style="padding: 2px;">&nbsp;</td>
                <td class="td" style="padding: 2px;">&nbsp;</td>
                <td class="td" style="padding: 2px;">&nbsp;</td>
                <td class="td" style="padding: 2px;">&nbsp;</td>
            </tr>
        @endfor
    </table>
    <br>
    <table style="width: 140px; float: right;" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <span>Dikoreksi :</span>
                <br>
                <span>Ka. Prodi</span>
                <br><br><br>
                <span>____________________</span>
                <br>
                <span>NIPY</span>
            </td>
        </tr>
    </table>
</body>

</html>
