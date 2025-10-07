@extends('layouts.app')

@section('title', 'Bahan Pemasukan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/bahan-pemasukan') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Bahan Pemasukan</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Scan Bahan</h4>
                </div>
                <form id="form-tambah">
                    <div class="card-body">
                        <div class="text-right mb-2">
                            <a data-toggle="collapse" href="#petunjuk" role="button" aria-expanded="false"
                                aria-controls="petunjuk">
                                <i class="fas fa-question-circle"></i>
                                Petunjuk
                            </a>
                        </div>
                        <div class="collapse" id="petunjuk">
                            <div class="alert alert-info rounded-0 mb-2">
                                <p>
                                    <strong>Petunjuk:</strong>
                                </p>
                                <ul class="px-4 mb-0">
                                    <li>Arahkan barcode scanner ke kode bahan dan lakukan scan.</li>
                                    <li>Setiap scan akan otomatis menambahkan data bahan ke daftar di bawah.</li>
                                    <li>Jika barcode yang sama dipindai lebih dari sekali, jumlahnya akan bertambah.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" class="form-control form-control-lg rounded-0 text-center" id="kode"
                                name="kode" maxlength="11" autofocus autocomplete="off">
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-primary rounded-0" id="btn-tambah"
                                onclick="form_tambah(false, true)">
                                <div id="btn-tambah-load" style="display: none;">
                                    <i class="fa fa-spinner fa-spin mr-1"></i>
                                    Memproses...
                                </div>
                                <span id="btn-tambah-text">Tambah</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Daftar Bahan</h4>
                </div>
                <div class="card-body pb-2">
                    @error('bahans')
                        <div class="alert alert-danger alert-dismissible show fade rounded-0 mb-2">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                {{ $message }}
                            </div>
                        </div>
                    @enderror
                </div>
                <form action="{{ url('admin/bahan-pemasukan/scan') }}" method="POST" autocomplete="off" id="form-submit">
                    @csrf
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-md">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 20px">No.</th>
                                        <th>Nama</th>
                                        <th>Satuan</th>
                                        <th class="text-center" style="width: 240px">Jumlah</th>
                                        <th class="text-center" style="width: 60px">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody id="bahan-tbody">
                                    <tr>
                                        <td class="text-center text-muted" colspan="5">- Bahan belum ditambahkan -</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                            <div id="btn-submit-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-submit-text">Buat Pemasukan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        var bahan_item = [];

        $('#form-tambah').on('submit', function(e) {
            e.preventDefault();
            form_tambah(false);
            var kode = $('#kode').val().trim();
            var key = bahan_item.length;
            $.ajax({
                url: "{{ url('admin/bahan-scan/tambah') }}" + '/' + kode,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        if (bahan_item.includes(data.id)) {
                            let jumlah = parseInt($('#bahan-jumlah-' + data.id).val()) || 0;
                            $('#bahan-jumlah-' + data.id).val(jumlah + 1);
                        } else {
                            bahan_set(key, data);
                        }
                        iziToast.success({
                            title: data.nama,
                            message: 'Berhasil ditambahkan',
                            position: 'topRight',
                            class: 'rounded-0',
                        });
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: response.message,
                            position: 'topRight',
                            class: 'rounded-0',
                        });
                        console.log(bahan_item);

                        if (bahan_item.length === 0) {
                            var tbody = '<tr>';
                            tbody +=
                                '<td class="text-center text-muted" colspan="5">- Bahan belum ditambahkan -</td>';
                            tbody += '</tr>';
                            $('#bahan-tbody').append(tbody);
                        }
                    }
                    form_tambah(true);
                },
                // masih salah
                error: function(xhr) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Kode Bahan tidak valid!',
                        position: 'topRight',
                        class: 'rounded-0',
                    });
                    if (bahan_item.length === 0) {
                        var tbody = '<tr>';
                        tbody +=
                            '<td class="text-center text-muted" colspan="5">- Bahan belum ditambahkan -</td>';
                        tbody += '</tr>';
                        $('#bahan-tbody').append(tbody);
                    }
                    form_tambah(true);
                },
            });
        });

        function bahan_set(key, value, is_old = false) {
            var jumlah = 1;
            if (is_old) {
                jumlah = value.jumlah;
            }
            var no = key + 1;
            var col = '<tr id="bahan-tr-' + value.id + '">';
            col += '<td class="text-center urutan">' + no + '</td>';
            col += '<td>' + value.nama + '<br>';
            col += '<small>(' + value.prodi.nama + ')</small>';
            col += '</td>';
            col += '<td>' + value.satuan_pinjam + '</td>';
            col += '<td>';
            col += '<div class="input-group">';
            col += '<div class="input-group-prepend">';
            col += '<button class="btn btn-secondary rounded-0" type="button" onclick="bahan_minus_item(' + value.id +
                ')">';
            col += '<i class="fas fa-minus"></i>';
            col += '</button>';
            col += '</div>';
            col += '<input type="text" class="form-control rounded-0 text-center" id="bahan-jumlah-' + value
                .id + '" name="bahans[' + key + '][jumlah]" value="' + jumlah + '">';
            col += '<div class="input-group-append">';
            col += '<button class="btn btn-secondary rounded-0" type="button" onclick="bahan_plus_item(' + value.id + ')">';
            col += '<i class="fas fa-plus"></i>';
            col += '</button>';
            col += '</div>';
            col += '</div>';
            col += '<input type="hidden" class="form-control rounded-0 text-center" name="bahans[' + key +
                '][id]" value="' + value.id + '" readonly>';
            col += '</td>';
            col += '<td class="text-center">';
            col += '<button type="button" class="btn btn-danger rounded-0" onclick="bahan_hapus(' + value.id + ')">';
            col += '<i class="fas fa-trash"></i>';
            col += '</button>';
            col += '</td>';
            col += '</tr>';
            $('#bahan-tbody').append(col);
            bahan_item.push(value.id);
        }

        function bahan_hapus(id) {
            $('#bahan-tr-' + id).remove();
            bahan_item = bahan_item.filter(item => item !== id);
            if (bahan_item.length == 0) {
                var tbody = '<tr>';
                tbody +=
                    '<td class="text-center text-muted" colspan="5">- Bahan belum ditambahkan -</td>';
                tbody += '</tr>';
                $('#bahan-tbody').append(tbody);
            } else {
                var urutan = $('.urutan');
                for (let i = 0; i < urutan.length; i++) {
                    urutan[i].innerText = i + 1;
                }
            }
        }

        function bahan_plus_item(id) {
            var jumlah = $('#bahan-jumlah-' + id);
            if (jumlah.val() < 100) {
                jumlah.val(parseInt(jumlah.val()) + 1);
            }
        }

        function bahan_minus_item(id) {
            var jumlah = $('#bahan-jumlah-' + id);
            if (jumlah.val() > 1) {
                jumlah.val(parseInt(jumlah.val()) - 1);
            }
        }

        var old_bahans = @json(session('old_bahans'));
        if (old_bahans !== null) {
            $('#bahan-tbody').empty();
            if (old_bahans.length > 0) {
                $.each(old_bahans, function(key, value) {
                    bahan_item.push(parseInt(value.id));
                    bahan_set(key, value, true);
                });
            }
        }
    </script>
    <script>
        function focus_scan_input() {
            $('#kode')[0].focus({
                preventScroll: true
            });
        }

        // selalu refocus setelah load
        $(document).ready(function() {
            focus_scan_input();
        });

        // kalau user klik apapun → refocus ke input scan
        $(document).on("click keydown", function() {
            focus_scan_input();
        });

        // kalau input scan kehilangan fokus → refocus lagi
        $("#kode").on("blur", function() {
            setTimeout(() => focus_scan_input(), 100);
        });

        // proses hasil scan (scanner biasanya kirim enter)
        $("#kode").on("keypress", function(e) {
            if (e.which === 13) { // Enter
                let kode = $(this).val().trim();
                if (kode !== "") {
                    $('#form-tambah').submit();
                    $(this).val("");
                }
                e.preventDefault();
            }
        });
    </script>
    <script>
        function form_tambah(status, submit = false) {
            if (status) {
                $('#btn-tambah').prop('disabled', false);
                $('#btn-tambah-text').show();
                $('#btn-tambah-load').hide();
                $('#btn-submit').prop('disabled', false);
                $('#bahan-tr-load').remove();
            } else {
                $('#btn-tambah').prop('disabled', true);
                $('#btn-tambah-text').hide();
                $('#btn-tambah-load').show();
                $('#btn-submit').prop('disabled', true);
                if (bahan_item.length === 0) {
                    $('#bahan-tbody').empty();
                }
                if (submit) {
                    $('#form-tambah').submit();
                } else {
                    var tbody = '<tr id="bahan-tr-load">';
                    tbody += '<td class="text-center" colspan="5">';
                    tbody += '<i class="fa fa-spinner fa-spin mr-1"></i>';
                    tbody += 'Memproses...';
                    tbody += '</td>';
                    tbody += '</tr>';
                    $('#bahan-tbody').append(tbody);
                }
            }
        }
    </script>
    <script>
        function form_submit() {
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit-text').hide();
            $('#btn-submit-load').show();
            $('#form-submit').submit();
        }
    </script>
@endsection
