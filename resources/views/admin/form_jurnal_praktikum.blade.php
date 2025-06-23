<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Jurnal Praktikum</title>
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
    <table style="width: 100%; margin-bottom: 5px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 140px; font-size: 12px; padding: 2px;">Program Studi</td>
            <td style="width: 10px; font-size: 12px; text-align: center; padding: 2px;">:</td>
            <td style="font-size: 12px; padding: 2px;"></td>
        </tr>
        <tr>
            <td style="width: 140px; font-size: 12px; padding: 2px;">Mata Kuliah</td>
            <td style="width: 10px; font-size: 12px; text-align: center; padding: 2px;">:</td>
            <td style="font-size: 12px; padding: 2px;"></td>
        </tr>
        <tr>
            <td style="width: 140px; font-size: 12px; padding: 2px;">Tingkat / Semester</td>
            <td style="width: 10px; font-size: 12px; text-align: center; padding: 2px;">:</td>
            <td style="font-size: 12px; padding: 2px;"></td>
        </tr>
        <tr>
            <td style="width: 140px; font-size: 12px; padding: 2px;">Perasat</td>
            <td style="width: 10px; font-size: 12px; text-align: center; padding: 2px;">:</td>
            <td style="font-size: 12px; padding: 2px;"></td>
        </tr>
        <tr>
            <td style="font-size: 12px; padding: 2px;" colspan="3">
                Praktikum Dilaksanakan Secara Kelompok / Individu
                <span style="opacity: 0.8">(*Coret Salah Satu)</span>
            </td>
        </tr>
    </table>
    <br>
    <div class="kode">
        <table class="table" cellspacing="0" cellpadding="0">
            <tr>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">Kode</td>
                <td class="td" style="font-weight: bold; text-align: center; font-size: 12px; padding: 2px 4px;">:
                </td>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">
                    F.SPMI.BMD/LAB.KESH.03/2025
                </td>
            </tr>
            <tr>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">Tanggal</td>
                <td class="td" style="font-weight: bold; text-align: center; font-size: 12px; padding: 2px 4px;">:
                </td>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">1 Juni 2025</td>
            </tr>
            <tr>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">Revisi</td>
                <td class="td" style="font-weight: bold; text-align: center; font-size: 12px; padding: 2px 4px;">:
                </td>
                <td class="td" style="font-weight: bold; font-size: 12px; padding: 2px 4px;">-</td>
            </tr>
        </table>
    </div>
    <div style="text-align: center">
        <span style="font-size: 14px; font-weight: bold; display: block;">PEMBAGIAN KELOMPOK</span>
    </div>
    <br>
    <table class="table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="td" style="font-weight: bold; text-align: center; padding: 4px;" colspan="2">I</td>
            <td class="td" style="font-weight: bold; text-align: center; padding: 4px;" colspan="2">II</td>
            <td class="td" style="font-weight: bold; text-align: center; padding: 4px;" colspan="2">III</td>
        </tr>
        <tr>
            <td class="td" style="padding: 4px;" colspan="2">Hari, Tanggal :</td>
            <td class="td" style="padding: 4px;" colspan="2">Hari, Tanggal :</td>
            <td class="td" style="padding: 4px;" colspan="2">Hari, Tanggal :</td>
        </tr>
        <tr>
            <td class="td" style="padding: 4px;" colspan="2">Waktu :</td>
            <td class="td" style="padding: 4px;" colspan="2">Waktu :</td>
            <td class="td" style="padding: 4px;" colspan="2">Waktu :</td>
        </tr>
        <tr>
            <td class="td" style="text-align: center; padding: 4px;">Nama Mahasiswa</td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;">TTD</td>
            <td class="td" style="text-align: center; padding: 4px;">Nama Mahasiswa</td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;">TTD</td>
            <td class="td" style="text-align: center; padding: 4px;">Nama Mahasiswa</td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;">TTD</td>
        </tr>
        <tr>
            <td class="td" style="padding: 4px;">
                @for ($i = 1; $i <= 20; $i++)
                    {{ $i }}.
                    <br>
                @endfor
            </td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;"></td>
            <td class="td" style="padding: 4px;">
                @for ($i = 1; $i <= 20; $i++)
                    {{ $i }}.
                    <br>
                @endfor
            </td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;"></td>
            <td class="td" style="padding: 4px;">
                @for ($i = 1; $i <= 20; $i++)
                    {{ $i }}.
                    <br>
                @endfor
            </td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;"></td>
        </tr>
        <tr>
            <td class="td" style="font-weight: bold; text-align: center; padding: 4px;" colspan="2">IV</td>
            <td class="td" style="font-weight: bold; text-align: center; padding: 4px;" colspan="2">V</td>
            <td class="td" style="font-weight: bold; text-align: center; padding: 4px;" colspan="2">VI</td>
        </tr>
        <tr>
            <td class="td" style="padding: 4px;" colspan="2">Hari, Tanggal :</td>
            <td class="td" style="padding: 4px;" colspan="2">Hari, Tanggal :</td>
            <td class="td" style="padding: 4px;" colspan="2">Hari, Tanggal :</td>
        </tr>
        <tr>
            <td class="td" style="padding: 4px;" colspan="2">Waktu :</td>
            <td class="td" style="padding: 4px;" colspan="2">Waktu :</td>
            <td class="td" style="padding: 4px;" colspan="2">Waktu :</td>
        </tr>
        <tr>
            <td class="td" style="text-align: center; padding: 4px;">Nama Mahasiswa</td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;">TTD</td>
            <td class="td" style="text-align: center; padding: 4px;">Nama Mahasiswa</td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;">TTD</td>
            <td class="td" style="text-align: center; padding: 4px;">Nama Mahasiswa</td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;">TTD</td>
        </tr>
        <tr>
            <td class="td" style="padding: 4px;">
                @for ($i = 1; $i <= 20; $i++)
                    {{ $i }}.
                    <br>
                @endfor
            </td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;"></td>
            <td class="td" style="padding: 4px;">
                @for ($i = 1; $i <= 20; $i++)
                    {{ $i }}.
                    <br>
                @endfor
            </td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;"></td>
            <td class="td" style="padding: 4px;">
                @for ($i = 1; $i <= 20; $i++)
                    {{ $i }}.
                    <br>
                @endfor
            </td>
            <td class="td" style="width: 60px; text-align: center; padding: 4px;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 140px;" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <span>Dosen Pengampu :</span>
                <br><br><br>
                <span>____________________</span>
            </td>
        </tr>
    </table>
</body>

</html>
