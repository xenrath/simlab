<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan</title>
    <style>
        body {
            padding: 0px 24px
        }

        .table-1,
        .table-1 th,
        .table-1 td,
        .table-2,
        .table-2 th,
        .table-2 td {
            border: 1px solid black;
        }

        .table-1 th {
            text-align: left;
            font-weight: bold;
            vertical-align: top;
        }

        .table-1 td {
            text-align: left;
            vertical-align: top;
        }

        .table-2 th {
            text-align: left;
            font-weight: bold;
        }

        .table-2 td {
            text-align: left;
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

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @foreach ($pinjams as $pinjam)
        <h3 style="text-align: center">LAPORAN PEMINJAMAN</h3>
        <br>
        <p>Detail Peminjaman</p>
        <table class="table-1" style="width: 100%;" cellspacing="0" cellpadding="10">
            <tr>
                <th style="width: 160px">Peminjam</th>
                <td>
                    {{ $pinjam->peminjam->nama }}
                </td>
            </tr>
            <tr>
                <th>Waktu</th>
                <td>
                    @php
                        $tanggal_awal = date('d M Y', strtotime($pinjam->tanggal_awal));
                        $tanggal_akhir = date('d M Y', strtotime($pinjam->tanggal_akhir));
                    @endphp
                    @if ($pinjam->praktik_id == '3' || ($pinjam->praktik_id == '1' && count($pinjam->kelompoks) == 0))
                        {{ $tanggal_awal }} - {{ $tanggal_akhir }}
                    @else
                        @if ($pinjam->peminjam->subprodi_id == '5')
                            {{ $tanggal_awal }}
                        @else
                            {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }}, {{ $tanggal_awal }}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th>Praktik</th>
                <td>
                    @if ($pinjam->praktik_id != null)
                        {{ $pinjam->praktik->nama }}
                        @if ($pinjam->praktik_id == '1')
                            ({{ $pinjam->ruang->nama }})
                        @else
                            ({{ $pinjam->keterangan }})
                        @endif
                    @else
                        Praktik Laboratorium ({{ $pinjam->ruang->nama }})
                    @endif
                </td>
            </tr>
            <tr>
                <th>Laboran</th>
                <td>{{ auth()->user()->nama }}</td>
            </tr>
            @if ($pinjam->bahan)
                <tr>
                    <th>Bahan</th>
                    <td>{{ $pinjam->bahan }}</td>
                </tr>
            @endif
        </table>
        @if (count($pinjam->detail_pinjams) > 0)
            <p>Detail Alat</p>
            <table class="table-2" style="width: 100%;" cellspacing="0" cellpadding="10">
                <tr>
                    <th style="text-align: center; width: 24px">No.</th>
                    <th>Nama Alat</th>
                    <th style="width: 120px">Ruang</th>
                    <th style="width: 80px">Jumlah</th>
                </tr>
                @foreach ($pinjam->detail_pinjams as $detail_pinjam)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $detail_pinjam->barang->nama }}</td>
                        <td>{{ $detail_pinjam->barang->ruang->nama }}</td>
                        <td>{{ $detail_pinjam->jumlah }} {{ $detail_pinjam->barang->satuan->singkatan }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
        <div class="page-break"></div>
    @endforeach
</body>

</html>
