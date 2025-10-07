<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Label Barcode</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }

        table.label-table {
            border-collapse: collapse;
            width: 100%;
        }

        table.label-table td {
            width: 120px;
            /* atur sesuai kebutuhan */
            text-align: center;
            padding: 10px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <p style="font-size: 16px; text-align: center;">
        {{ $bahan->nama }} ({{ $bahan->prodi->nama }})
    </p>
    <table class="label-table" cellspacing="0" cellpadding="0">
        @for ($i = 0; $i < $jumlah; $i++)
            @if ($i % 4 == 0)
                <tr>
            @endif
            <td style="border: 1px solid black; text-align: center; padding: 10px 10px 6px 10px;">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($bahan->kode, 'C128', 1, 30) }}" alt="barcode">
                <div style="margin-top: 2px;">{{ $bahan->kode }}</div>
            </td>
            @if (($i + 1) % 4 == 0)
                </tr>
            @endif
        @endfor
        @php
            $sisa = $jumlah % 4;
        @endphp
        @if ($sisa > 0)
            @for ($i = 0; $i < 4 - $sisa; $i++)
                <td style="border: 1px solid black;"></td>
            @endfor
            </tr>
        @endif
    </table>
</body>

</html>
