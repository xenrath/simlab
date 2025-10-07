@extends('layouts.app')

@section('title', 'Bahan Pemasukan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('laboran/bidan/bahan-pemasukan') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Bahan Pemasukan</h1>
        </div>
        <div class="section-body">
            <form action="{{ url('laboran/bidan/bahan-pemasukan/manual') }}" method="POST" autocomplete="off" id="form-submit">
                @csrf
                <div class="card rounded-0 mb-3">
                    <div class="card-header">
                        <h4>Daftar Bahan</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-bahan">
                                Pilih
                            </button>
                        </div>
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
                        @if ($errors->has('bahans.*.jumlah'))
                            <div class="alert alert-danger alert-dismissible show fade rounded-0 mb-2">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    Pastikan jumlah bahan terisi dengan benar!
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card rounded-0 mb-3" id="bahan-kosong">
                    <div class="card-body p-5 text-center">
                        <span class="text-muted">- Belum ada bahan yang di tambahkan -</span>
                    </div>
                </div>
                <div class="row" id="bahan-list"></div>
                <div class="text-right">
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
    </section>
    <div class="modal fade" id="modal-bahan" data-backdrop="static" role="dialog" aria-labelledby="modal-bahan">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-3 border-bottom">
                    <h5 class="modal-title">Data Bahan</h5>
                </div>
                <div class="modal-header py-3 border-bottom shadow-sm flex-column align-items-stretch">
                    <div class="input-group mb-2">
                        <input type="search" class="form-control rounded-0" id="bahan-nama" autocomplete="off"
                            placeholder="Cari Nama Bahan">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0" onclick="bahan_cari()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <select class="custom-select custom-select-sm rounded-0" id="bahan-page" name="bahan_page"
                        style="width: 60px;" onchange="bahan_cari()">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="modal-body">
                    <div id="modal-card-bahan">
                        @foreach ($bahans as $bahan)
                            <div class="card border rounded-0 mb-2">
                                <label for="bahan-checkbox-{{ $bahan->id }}"
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">
                                    <span class="font-weight-normal">
                                        {{ $bahan->nama }}
                                        <br>
                                        <small class="font-weight-light">({{ $bahan->prodi->nama }})</small>
                                    </span>
                                    <div class="custom-checkbox custom-control checkbox-square">
                                        <input type="checkbox" class="custom-control-input"
                                            id="bahan-checkbox-{{ $bahan->id }}"
                                            onclick="bahan_tambah({{ $bahan->id }})">
                                        <label for="bahan-checkbox-{{ $bahan->id }}"
                                            class="custom-control-label"></label>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal-card-bahan-loading" class="text-center p-4" style="display: none">
                        <span class="text-muted">
                            <i class="fas fa-spinner fa-spin fa-sm mr-1"></i>
                            Loading...
                        </span>
                    </div>
                    <div id="modal-card-bahan-empty" class="card border rounded-0 mb-2" style="display: none">
                        <div class="card-body text-center">
                            <span class="text-muted">- Data tidak ditemukan -</span>
                        </div>
                    </div>
                    <div id="modal-card-bahan-limit" class="text-center">
                        <small class="text-muted">Cari dengan <strong>kata kunci</strong> lebih detail</small>
                        <br>
                        <small class="text-muted">
                            Menampilkan maksimal
                            <span id="span-bahan-page">10</span>
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
        $('#bahan-nama').on('search', function() {
            bahan_cari();
        });

        var bahan_item = [];

        function bahan_cari() {
            let bahan_nama = $('#bahan-nama').val();
            let bahan_page = $('#bahan-page').val();

            $('#modal-card-bahan').empty();
            $('#modal-card-bahan-loading').show();
            $('#modal-card-bahan-empty').hide();
            $('#modal-card-bahan-limit').hide();
            $.ajax({
                url: "{{ url('laboran/bidan/bahan-cari') }}",
                type: "GET",
                data: {
                    "bahan_nama": bahan_nama,
                    "bahan_page": bahan_page,
                },
                dataType: "json",
                success: function(data) {
                    $('#modal-card-bahan-loading').hide();
                    if (data.length) {
                        $('#modal-card-bahan').show();
                        $('#modal-card-bahan-empty').hide();
                        $('#modal-card-bahan-limit').show();
                        $.each(data, function(key, value) {
                            bahan_modal(value, bahan_item.includes(value.id));
                        });
                        $('#span-bahan-page').text(bahan_page);
                    } else {
                        $('#modal-card-bahan').hide();
                        $('#modal-card-bahan-empty').show();
                        $('#modal-card-bahan-limit').hide();
                    }
                },
            });
        }

        function bahan_modal(data, is_selected) {
            if (is_selected) {
                var checked = 'checked';
            } else {
                var checked = '';
            }
            var card_items = '<div class="card border rounded-0 shadow-sm mb-2">';
            card_items +=
                '<label for="bahan-checkbox-' + data.id +
                '" class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">';
            card_items += '<span class="font-weight-normal">';
            card_items += data.nama + '<br>';
            card_items += '<small class="font-weight-light">(' + data.prodi.nama + ')</small>';
            card_items += '</span>';
            card_items += '<div class="custom-checkbox custom-control checkbox-square">';
            card_items +=
                '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="bahan-checkbox-' + data
                .id +
                '" onclick="bahan_tambah(' + data.id + ')" ' + checked + ' >';
            card_items += '<label for="bahan-checkbox-' + data.id + '" class="custom-control-label"></label>';
            card_items += '</div>';
            card_items += '</label>';
            card_items += '</div>';

            $('#modal-card-bahan').append(card_items);
        }

        function bahan_tambah(id) {
            var check = $('#bahan-checkbox-' + id).prop('checked');
            if (check) {
                if (!bahan_item.includes(id)) {
                    bahan_loading(true, id);
                    var key = bahan_item.length;
                    $.ajax({
                        url: "{{ url('laboran/bidan/bahan-tambah') }}" + '/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            bahan_set(key, data);
                            bahan_loading(false, id);
                        },
                    });
                    bahan_item.push(id);
                }
            } else {
                bahan_hapus(id);
            }
            if (bahan_item.length > 0) {
                $('#bahan-kosong').hide();
            } else {
                $('#bahan-kosong').show();
            }
        }

        function bahan_set(key, value, is_old = false) {
            var jumlah = 1;
            if (is_old) {
                jumlah = value.jumlah;
            }
            var col = '<div id="bahan-col-' + value.id + '" class="col-12 col-md-6 col-lg-4">';
            col += '<div class="card rounded-0 mb-3">';
            col += '<div class="card-body">';
            col += '<div class="d-flex justify-content-between align-items-start">';
            col += '<span>';
            col += '<strong>' + value.nama + '</strong><br>';
            col += '<small>(' + value.prodi.nama + ')</small>';
            col += '</span>';
            col +=
                '<button class="btn btn-danger rounded-0" type="button" onclick="bahan_hapus(' + value
                .id +
                ')">';
            col += '<i class="fas fa-trash"></i>';
            col += '</button>';
            col += '</div>';
            col += '</div>';
            col += '<div class="card-body border-top">';
            col += '<div class="input-group">';
            col += '<input type="number" class="form-control rounded-0 text-center" id="bahan-jumlah-' + value.id +
                '" name="bahans[' + key + '][jumlah]" value="' + jumlah + '">';
            col += '<div class="input-group-append">';
            col += '<div class="input-group-text bg-light rounded-0">' + value.satuan_pinjam + '</div>';
            col += '</div>';
            col += '</div>';
            col += '</div>';
            col += '<input type="hidden" class="form-control rounded-0 text-center" name="bahans[' + key +
                '][bahan_id]" value="' +
                value.id + '" readonly>';
            col += '<input type="hidden" class="form-control rounded-0 text-center" name="bahans[' + key +
                '][bahan_nama]" value="' +
                value.nama + '" readonly>';
            col += '<input type="hidden" class="form-control rounded-0 text-center" name="bahans[' + key +
                '][prodi_id]" value="' +
                value.prodi.id + '" readonly>';
            col += '<input type="hidden" class="form-control rounded-0 text-center" name="bahans[' + key +
                '][prodi_nama]" value="' +
                value.prodi.nama + '" readonly>';
            col += '<input type="hidden" class="form-control rounded-0 text-center" name="bahans[' + key +
                '][satuan_pinjam]" value="' +
                value.satuan_pinjam + '" readonly>';
            col += '</div>';
            col += '</div>';
            col += '</div>';
            $('#bahan-list').append(col);
        }

        function bahan_hapus(id) {
            $('#bahan-col-' + id).remove();
            bahan_item = bahan_item.filter(item => item !== id);
            $('#bahan-checkbox-' + id).prop('checked', false);
            if (bahan_item.length == 0) {
                $('#bahan-kosong').show();
            }
        }

        var old_bahans = @json(session('old_bahans'));
        if (old_bahans !== null) {
            $('#bahan-list').empty();
            if (old_bahans.length > 0) {
                $('#bahan-kosong').hide();
                $.each(old_bahans, function(key, value) {
                    bahan_item.push(parseInt(value.id));
                    $('#bahan-checkbox-' + value.id).prop('checked', true);
                    bahan_set(key, value, true);
                });
            } else {
                $('#bahan-kosong').show();
            }
        }

        function bahan_loading(is_aktif, id) {
            if (is_aktif) {
                var col = '<div id="bahan-loading-' + id + '" class="col-12 col-md-6 col-lg-4">';
                col += '<div class="card mb-3 rounded-0">';
                col += '<div class="card-body text-center p-5">';
                col += '<i class="fa fa-spinner fa-spin"></i>';
                col += '</div>';
                col += '</div>';
                col += '</div>';
                $('#bahan-list').append(col);
                $('#btn-submit').prop('disabled', true);
            } else {
                $('#bahan-loading-' + id).remove();
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
