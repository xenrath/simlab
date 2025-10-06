<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Peminjaman Laboratorium Terpadu</title>
    <style>
        body {
            padding: 0px;
            font-size: 14px;
            line-height: 1.4;
        }

        .logo {
            width: 100px;
            position: absolute;
        }

        .header {
            margin-left: 100px;
            height: 100px;
            text-align: center;
        }

        .header .h1 {
            font-size: 24px;
            font-weight: bold;
            display: block;
        }

        .header .h2 {
            font-size: 16px;
            font-weight: bold;
            display: block;
        }

        .header .p {
            font-size: 14px;
            display: block;
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
            padding: 8px;
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
    <div style="margin-top: 200px; text-align: center;">
        <span style="font-size: 20px; font-weight: bold;">LAPORAN PEMINJAMAN</span>
        <br>
        <span style="font-size: 20px; font-weight: bold;">SISTEM INFORMASI MANAGEMEN LABORATORIUM</span>
        <br><br><br>
        <span style="font-size: 16px; font-weight: bold;">Laboran</span>
        <br>
        <span style="font-size: 20px; font-weight: bold;">{{ auth()->user()->nama }}</span>
        <br><br><br>
        <span style="font-size: 16px;">
            Periode Waktu:
            {{ Carbon\Carbon::parse($tanggal_awal)->translatedFormat('d M Y') }}
            s/d
            {{ Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d M Y') }}
        </span>
    </div>
    <div class="page-break"></div>
    @foreach ($pinjams as $key => $pinjam)
        <table class="table" cellspacing="0" cellpadding="0">
            <tr>
                <td class="td" colspan="4">
                    Kategori Peminjaman
                    <span style="float: right;">
                        @if ($pinjam->praktik_id == 1)
                            <img src="{{ asset('storage/uploads/asset/check-square-regular.svg') }}"
                                style="height: 16px; vertical-align: middle; margin-right: 2px;">
                        @else
                            <img src="{{ asset('storage/uploads/asset/square-regular.svg') }}"
                                style="height: 16px; vertical-align: middle; margin-right: 2px;">
                        @endif
                        Laboratorium
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        @if ($pinjam->praktik_id == 2)
                            <img src="{{ asset('storage/uploads/asset/check-square-regular.svg') }}"
                                style="height: 16px; vertical-align: middle; margin-right: 2px;">
                        @else
                            <img src="{{ asset('storage/uploads/asset/square-regular.svg') }}"
                                style="height: 16px; vertical-align: middle; margin-right: 2px;">
                        @endif
                        Dalam Kelas
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        @if ($pinjam->praktik_id == 3)
                            <img src="{{ asset('storage/uploads/asset/check-square-regular.svg') }}"
                                style="height: 16px; vertical-align: middle; margin-right: 2px;">
                        @else
                            <img src="{{ asset('storage/uploads/asset/square-regular.svg') }}"
                                style="height: 16px; vertical-align: middle; margin-right: 2px;">
                        @endif
                        PKL
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        @if ($pinjam->praktik_id == 4)
                            <img src="{{ asset('storage/uploads/asset/check-square-regular.svg') }}"
                                style="height: 16px; vertical-align: middle; margin-right: 2px;">
                        @else
                            <img src="{{ asset('storage/uploads/asset/square-regular.svg') }}"
                                style="height: 16px; vertical-align: middle; margin-right: 2px;">
                        @endif
                        Pinjam Ruang
                    </span>
                </td>
            </tr>
            <tr>
                <td class="td" style="width: 100px;">Nama Peminjam</td>
                <td class="td">{{ $pinjam->peminjam->nama }}</td>
                <td class="td" style="width: 100px;">Tanggal Pinjam</td>
                <td class="td">
                    @php
                        $tanggal_awal = Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d M Y');
                        $tanggal_akhir = Carbon\Carbon::parse($pinjam->tanggal_akhir)->translatedFormat('d M Y');
                    @endphp
                    @if ($pinjam->praktik_id == '3' || ($pinjam->praktik_id == '1' && count($pinjam->kelompoks) == 0))
                        {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                    @else
                        @if ($pinjam->peminjam->subprodi_id == '5')
                            {{ $tanggal_awal }}
                        @else
                            {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB, {{ $tanggal_awal }}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td" style="width: 100px;">NIM</td>
                <td class="td">{{ $pinjam->peminjam->kode }}</td>
                <td class="td" style="width: 100px;">Ruang Lab</td>
                <td class="td">
                    @if ($pinjam->praktik_id == 1 || $pinjam->praktik_id == 4)
                        {{ $pinjam->ruang->nama }}
                    @else
                        {{ $pinjam->keterangan }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="td" style="width: 100px;">Prodi</td>
                <td class="td">
                    {{ $pinjam->peminjam->subprodi->jenjang }}
                    {{ $pinjam->peminjam->subprodi->nama }}
                </td>
                <td class="td" style="width: 100px;">Mata Kuliah</td>
                <td class="td">{{ $pinjam->matakuliah }}</td>
            </tr>
            <tr>
                <td class="td" style="width: 100px;">Semester</td>
                <td class="td">-</td>
                <td class="td" style="width: 100px;">Praktik</td>
                <td class="td">-</td>
            </tr>
        </table>
        @if (count($pinjam->detail_pinjams) > 0)
            <br>
            <table class="table" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="td" style="width: 20px; font-weight: bold; text-align: center;">No</td>
                    <td class="td" style="font-weight: bold; text-align: center;">Nama Alat / Barang</td>
                    <td class="td" style="width: 80px; font-weight: bold; text-align: center;">Jumlah</td>
                </tr>
                @foreach ($pinjam->detail_pinjams as $detail_pinjam)
                    <tr>
                        <td class="td" style="text-align: center;">{{ $loop->iteration }}</td>
                        <td class="td">{{ $detail_pinjam->barang->nama }}</td>
                        <td class="td" style="text-align: center;">
                            {{ $detail_pinjam->jumlah }}
                            {{ $detail_pinjam->barang->satuan->singkatan }}
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
        @if ($key < count($pinjams) - 1)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
