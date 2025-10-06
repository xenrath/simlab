@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/perawat/menunggu') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Menunggu</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->praktik->nama }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Waktu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->jam_awal }}-{{ $pinjam->jam_akhir }} WIB,
                                    {{ Carbon\Carbon::parse($pinjam->tanggal_awal)->translatedFormat('d M Y') }}
                                    @php
                                        $now = Carbon\Carbon::now()->format('Y-m-d');
                                        $expire = date('Y-m-d', strtotime($pinjam->tanggal_awal));
                                    @endphp
                                    @if ($now > $expire)
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Ruang Lab</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->ruang->nama }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->laboran->nama ?? $pinjam->ruang->laboran->nama }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Mata Kuliah</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->matakuliah }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->praktik_keterangan }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Dosen</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->dosen }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <strong>Kelas</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->kelas }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (count($data_kelompok))
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>Detail Kelompok</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <strong>Ketua</strong>
                                    </div>
                                    <div class="col-md-8">
                                        {{ $data_kelompok['ketua']['kode'] }} | {{ $data_kelompok['ketua']['nama'] }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Anggota</strong>
                                    </div>
                                    <div class="col-md-8">
                                        @php
                                            $anggotas = $data_kelompok['anggota'];
                                        @endphp
                                        <ul class="p-0" style="list-style: none">
                                            @foreach ($anggotas as $anggota)
                                                <li>{{ $anggota['kode'] }} | {{ $anggota['nama'] }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <form action="{{ url('peminjam/perawat/menunggu/' . $pinjam->id) }}" method="POST" autocomplete="off"
                id="form-submit">
                @csrf
                @method('put')
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>List Barang</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-barang">
                                Pilih
                            </button>
                        </div>
                    </div>
                    <div class="card-body pb-2">
                        @error('barangs')
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
                </div>
                <div class="card rounded-0 mb-3" id="barang-kosong">
                    <div class="card-body p-5 text-center">
                        <span class="text-muted">- Belum ada barang yang di tambahkan -</span>
                    </div>
                </div>
                <div class="row" id="barang-list"></div>
                <div class="mt-4 mb-2 text-right">
                    <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                        <div id="btn-submit-load" style="display: none;">
                            <i class="fa fa-spinner fa-spin mr-1"></i>
                            Memproses...
                        </div>
                        <span id="btn-submit-text">Perbarui Peminjaman</span>
                    </button>
                </div>
            </form>
        </div>
    </section>
    <div class="modal fade" id="modal-barang" data-backdrop="static" role="dialog" aria-labelledby="modal-barang">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-3 border-bottom">
                    <h5 class="modal-title">Data Barang</h5>
                </div>
                <div class="modal-header py-3 border-bottom shadow-sm flex-column align-items-stretch">
                    <div class="input-group mb-2">
                        <input type="search" class="form-control rounded-0" id="barang-keyword" autocomplete="off"
                            placeholder="Cari Nama Barang">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0" onclick="barang_cari()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <select class="custom-select custom-select-sm rounded-0" id="barang-page" name="barang_page"
                        style="width: 60px;" onchange="barang_cari()">
                        <option value="10" {{ Request::get('page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ Request::get('page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ Request::get('page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ Request::get('page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="modal-body">
                    <div id="modal-card-barang">
                        @foreach ($barangs as $barang)
                            <div class="card border rounded-0 mb-2">
                                <label for="barang-checkbox-{{ $barang->id }}"
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">
                                    <span class="font-weight-normal">
                                        {{ $barang->nama }}
                                        <br>
                                        <small class="font-weight-light">({{ $barang->ruang->nama }})</small>
                                    </span>
                                    <div class="custom-checkbox custom-control checkbox-square">
                                        <input type="checkbox" class="custom-control-input"
                                            id="barang-checkbox-{{ $barang->id }}"
                                            onclick="barang_tambah({{ $barang->id }})">
                                        <label for="barang-checkbox-{{ $barang->id }}"
                                            class="custom-control-label"></label>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal-card-barang-loading" class="text-center p-4" style="display: none">
                        <span class="text-muted">
                            <i class="fas fa-spinner fa-spin fa-sm mr-1"></i>
                            Loading...
                        </span>
                    </div>
                    <div id="modal-card-barang-empty" class="card border rounded-0 mb-2" style="display: none">
                        <div class="card-body text-center">
                            <span class="text-muted">- Data tidak ditemukan -</span>
                        </div>
                    </div>
                    <div id="modal-card-barang-limit" class="text-center">
                        <small class="text-muted">Cari dengan <strong>kata kunci</strong> lebih detail</small>
                        <br>
                        <small class="text-muted">
                            Menampilkan maksimal
                            <span id="span-barang-page">10</span>
                            data
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-top shadow-sm justify-content-end">
                    <button type="button" class="btn btn-primary rounded-0" data-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#barang-keyword').on('search', function() {
            barang_cari();
        });

        var barang_item = [];

        function barang_cari() {
            let barang_keyword = $('#barang-keyword').val();
            let barang_page = $('#barang-page').val();
            $('#modal-card-barang').empty();
            $('#modal-card-barang-loading').show();
            $('#modal-card-barang-empty').hide();
            $('#modal-card-barang-limit').hide();
            $.ajax({
                url: "{{ url('peminjam/perawat/barang-cari') }}",
                type: "GET",
                data: {
                    "keyword": barang_keyword,
                    "page": barang_page,
                },
                dataType: "json",
                success: function(data) {
                    $('#modal-card-barang-loading').hide();
                    if (data.length) {
                        $('#modal-card-barang').show();
                        $('#modal-card-barang-empty').hide();
                        $('#modal-card-barang-limit').show();
                        $.each(data, function(key, value) {
                            barang_modal(value, barang_item.includes(value.id));
                        });
                        $('#span-barang-page').text(barang_page);
                    } else {
                        $('#modal-card-barang').hide();
                        $('#modal-card-barang-empty').show();
                        $('#modal-card-barang-limit').hide();
                    }
                },
            });
        }

        function barang_modal(data, is_selected) {
            if (is_selected) {
                var checked = 'checked';
            } else {
                var checked = '';
            }
            var card_items = '<div class="card border rounded-0 shadow-sm mb-2">';
            card_items +=
                '<label for="barang-checkbox-' + data.id +
                '" class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">';
            card_items += '<span class="font-weight-normal">';
            card_items += data.nama + '<br>';
            card_items += '<small class="font-weight-light">(' + data.ruang.nama + ')</small>';
            card_items += '</span>';
            card_items += '<div class="custom-checkbox custom-control">';
            card_items +=
                '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="barang-checkbox-' + data
                .id +
                '" onclick="barang_tambah(' + data.id + ')" ' + checked + ' >';
            card_items += '<label for="barang-checkbox-' + data.id + '" class="custom-control-label"></label>';
            card_items += '</div>';
            card_items += '</label>';
            card_items += '</div>';

            $('#modal-card-barang').append(card_items);
        }

        function barang_tambah(id) {
            var check = $('#barang-checkbox-' + id).prop('checked');
            if (check) {
                if (!barang_item.includes(id)) {
                    barang_loading(true, id);
                    var key = barang_item.length;
                    $.ajax({
                        url: "{{ url('peminjam/perawat/barang-tambah') }}" + '/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            barang_set(key, data);
                            barang_loading(false, id);
                        },
                    });
                    barang_item.push(id);
                }
            } else {
                barang_hapus(id);
            }
            if (barang_item.length > 0) {
                $('#barang-kosong').hide();
                $('#barang-alert').show();
            } else {
                $('#barang-kosong').show();
                $('#barang-alert').hide();
            }
        }

        function barang_set(key, value, is_old = false) {
            var jumlah = 1;
            if (is_old) {
                jumlah = value.jumlah;
            }
            var col = '<div id="barang-col-' + value.id + '" class="col-12 col-md-6 col-lg-4">';
            col += '<div class="card rounded-0 mb-3">';
            col += '<div class="card-body">';
            col += '<div class="d-flex justify-content-between align-items-start">';
            col += '<span>';
            col += '<strong>' + value.nama + '</strong><br>';
            col += '<small>(' + value.ruang.nama + ')</small>';
            col += '</span>';
            col +=
                '<button class="btn btn-danger rounded-0" type="button" id="minus-' + value.id +
                '" onclick="barang_hapus(' + value
                .id +
                ')">';
            col += '<i class="fas fa-trash"></i>';
            col += '</button>';
            col += '</div>';
            col += '</div>';
            col += '<div class="card-body border-top">';
            col += '<div class="input-group">';
            col += '<div class="input-group-prepend">';
            col +=
                '<button class="btn btn-secondary rounded-0" type="button" id="minus-' + value.id +
                '" onclick="barang_minus_item(' + value
                .id +
                ')">';
            col += '<i class="fas fa-minus"></i>';
            col += '</button>';
            col += '</div>';
            col += '<input type="text" class="form-control rounded-0 text-center" id="barang-jumlah-' + value.id +
                '" name="barangs[' + key + '][jumlah]" value="' + jumlah + '" readonly>';
            col += '<div class="input-group-append">';
            col +=
                '<button class="btn btn-secondary rounded-0" type="button" id="plus-' + value.id +
                '" onclick="barang_plus_item(' +
                value
                .id +
                ')">';
            col += '<i class="fas fa-plus"></i>';
            col += '</button>';
            col += '</div>';
            col += '</div>';
            col += '</div>';
            col += '<input type="hidden" class="form-control rounded-0 text-center" id="barang-jumlah-' + value.id +
                '" name="barangs[' + key + '][id]" value=' + value.id + ' readonly>';
            col += '</div>';
            col += '</div>';
            col += '</div>';
            $('#barang-list').append(col);
        }

        function barang_hapus(id) {
            $('#barang-col-' + id).remove();
            barang_item = barang_item.filter(item => item !== id);
            $('#barang-checkbox-' + id).prop('checked', false);
            if (barang_item.length == 0) {
                $('#barang-kosong').show();
                $('#barang-alert').hide();
            }
        }

        function barang_plus_item(id) {
            var jumlah = $('#barang-jumlah-' + id);
            if (jumlah.val() < 100) {
                jumlah.val(parseInt(jumlah.val()) + 1);
            }
        }

        function barang_minus_item(id) {
            var jumlah = $('#barang-jumlah-' + id);
            if (jumlah.val() > 1) {
                jumlah.val(parseInt(jumlah.val()) - 1);
            }
        }

        var old_barangs = @json(session('old_barangs') ?? $detail_pinjams);
        if (old_barangs !== null) {
            $('#barang-list').empty();
            if (old_barangs.length > 0) {
                $('#barang-kosong').hide();
                $('#barang-alert').show();
                $.each(old_barangs, function(key, value) {
                    barang_item.push(parseInt(value.id));
                    $('#barang-checkbox-' + value.id).prop('checked', true);
                    barang_set(key, value, true);
                });
            } else {
                $('#barang-kosong').show();
                $('#barang-alert').hide();
            }
        }

        function barang_loading(is_aktif, id) {
            if (is_aktif) {
                var col = '<div id="barang-loading-' + id + '" class="col-12 col-md-6 col-lg-4">';
                col += '<div class="card mb-3 rounded-0">';
                col += '<div class="card-body text-center p-5">';
                col += '<i class="fa fa-spinner fa-spin"></i>';
                col += '</div>';
                col += '</div>';
                col += '</div>';
                $('#barang-list').append(col);
                $('#btn-submit').prop('disabled', true);
            } else {
                $('#barang-loading-' + id).remove();
                $('#btn-submit').prop('disabled', false);
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
