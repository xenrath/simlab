@extends('layouts.app')

@section('title', 'Buat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Buat Peminjaman</h1>
        </div>
        @if (session('errors'))
            <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">GAGAL !</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <ul class="px-3 mb-0">
                        @foreach (session('errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="section-body">
            <form action="{{ url('admin/peminjaman/create/store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Peminjam</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tamu_id" class="w-100">
                                <span>Pilih Tamu</span>
                                <span class="float-right">
                                    <a href="{{ url('admin/pengguna/tamu') }}">Tambah Tamu</a>
                                </span>
                            </label>
                            <select class="form-control select2" name="tamu_id" id="tamu_id">
                                @foreach ($tamus as $tamu)
                                    <option value="{{ $tamu->id }}" {{ old('tamu_id') == $tamu->id }}>
                                        {{ $tamu->institusi }} - {{ $tamu->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lama">Lama Peminjaman
                                <small>(per Hari)</small>
                            </label>
                            <input type="number" name="lama" id="lama" class="form-control"
                                value="{{ old('lama') }}">
                        </div>
                        <div class="form-group">
                            <label for="keperluan">Keperluan Peminjaman
                                <small>(opsional)</small>
                            </label>
                            <textarea name="keperluan" id="keperluan" cols="30" rows="10" class="form-control" style="height: 80px">{{ old('keperluan') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>Daftar Barang</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalBarang">
                                <i class="fas fa-check-square mr-2"></i>Pilih
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card" id="card_barang_kosong">
                    <div class="card-body p-5 text-center">
                        <span>- Belum ada barang yang di tambahkan -</span>
                    </div>
                </div>
                <div class="row" id="row_items">
                </div>
                <hr>
                <div class="float-right">
                    <button type="submit" class="btn btn-primary">Buat Pinjaman</button>
                </div>
            </form>
        </div>
    </section>
    <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarang" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Barang</h5>
                </div>
                <div class="modal-header pt-0 pb-3 border-bottom shadow-sm">
                    <div class="input-group">
                        <input type="text" class="form-control" id="keyword" autocomplete="off"
                            onkeypress="search_handler(event)" placeholder="Cari barang">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" onclick="search_item()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="modal_card_barang">
                        @php
                            $item_id = [];
                            if (session('data_items')) {
                                foreach (session('data_items') as $data_item) {
                                    array_push($item_id, $data_item['id']);
                                }
                            }
                        @endphp
                        @foreach ($barangs as $barang)
                            <div class="card border rounded shadow-sm mb-2">
                                <label for="checkbox-{{ $barang->id }}"
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">
                                    <span>
                                        <strong>{{ $barang->nama }}</strong><br>
                                        <small style="line-height: 1.5">({{ $barang->ruang->nama }})</small>
                                    </span>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                            id="checkbox-{{ $barang->id }}" onclick="add_item({{ $barang->id }})"
                                            {{ in_array($barang->id, $item_id) ? 'checked' : '' }}>
                                        <label for="checkbox-{{ $barang->id }}" class="custom-control-label"></label>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal_card_barang_kosong">
                        <div class="card border rounded shadow-sm mb-2">
                            <div class="card-body p-0">
                                <p class="py-2 px-3 m-0 text-center text-muted">- Barang tidak ditemukan -</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let modal_card_barang = document.getElementById('modal_card_barang');
        let modal_card_barang_kosong = document.getElementById('modal_card_barang_kosong');

        var item_id = [];

        var data_items = @json(session('data_items'));
        if (data_items !== null) {
            console.log(data_items);
            if (data_items.length > 0) {
                $('#card_barang_kosong').hide();
                $('#row_items').empty();
                $.each(data_items, function(key, value) {
                    item_id.push(value.id);
                    set_items(key, value, true);
                });
            }
        } else {
            $('#card_barang_kosong').show();
        }

        function search_handler(event) {
            if (event.key === "Enter") {
                search_item();
            }
        }

        function search_item() {
            let keyword = document.getElementById('keyword').value;
            $('#modal_card_barang').empty();
            $.ajax({
                url: "{{ url('admin/peminjaman/search_items') }}",
                type: "GET",
                data: {
                    "keyword": $('#keyword').val()
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        console.log(data);
                        $('#modal_card_barang').show();
                        $('#modal_card_barang_kosong').hide();
                        $.each(data, function(key, value) {
                            modal_items(value, item_id.includes(value.id));
                        });
                    } else {
                        $('#modal_card_barang').hide();
                        $('#modal_card_barang_kosong').show();
                    }
                },
            });
        }

        function modal_items(data, is_selected) {
            if (is_selected) {
                var checked = 'checked';
            } else {
                var checked = '';
            }
            var card_items = '<div class="card border rounded shadow-sm mb-2">';
            card_items +=
                '<label for="checkbox-' + data.id +
                '" class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">';
            card_items += '<span>';
            card_items += '<strong>' + data.nama + '</strong><br>';
            card_items += '<span>(' + data.ruang.nama + ')</span>';
            card_items += '</span>';
            card_items += '<div class="custom-checkbox custom-control">';
            card_items +=
                '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-' + data.id +
                '" onclick="add_item(' + data.id + ')" ' + checked + ' >';
            card_items += '<label for="checkbox-' + data.id + '" class="custom-control-label"></label>';
            card_items += '</div>';
            card_items += '</label>';
            card_items += '</div>';

            $('#modal_card_barang').append(card_items);
        }

        function add_item(id) {
            if (!item_id.includes(id)) {
                var key = item_id.length;
                $.ajax({
                    url: "{{ url('admin/peminjaman/add_item') }}" + '/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        set_items(key, data);
                    },
                });
                item_id.push(id);
            }
            $('#card_barang_kosong').hide();
        }

        function set_items(key, data, is_session = false) {
            var total = 1;
            if (is_session) {
                total = data.total;
                $('#checkbox-' + data.id).prop('checked', true);
            }
            var col = '<div class="col-12 col-md-6 col-lg-4" id="col_item-' + data.id + '">';
            col += '<div class="card mb-3">';
            col += '<div class="card-body">';
            col += '<span>';
            col += '<strong>' + data.nama + '</strong><br>';
            col += '<small>(' + data.ruang_nama + ')</small>';
            col += '</span>';
            col += '<hr>';
            col += '<div class="d-flex justify-content-between">';
            col += '<div class="input-group" style="width: 160px">';
            col += '<div class="input-group-prepend">';
            col +=
                '<button class="btn btn-secondary" type="button" id="minus-' + data.id + '" onclick="minus_item(' + data
                .id +
                ')">';
            col += '<i class="fas fa-minus"></i>';
            col += '</button>';
            col += '</div>';
            col += '<input type="text" class="form-control text-center" id="jumlah-barang-' + data.id + '" name="items[' +
                data
                .id + ']" value=' + total + ' readonly>';
            col += '<div class="input-group-append">';
            col +=
                '<button class="btn btn-secondary" type="button" id="plus-' + data.id + '" onclick="plus_item(' + data.id +
                ')">';
            col += '<i class="fas fa-plus"></i>';
            col += '</button>';
            col += '</div>';
            col += '</div>';
            col += '<button type="button" class="btn btn-danger" onclick="delete_item(' + data.id + ')">';
            col += '<i class="fas fa-trash"></i>';
            col += '</button>';
            col += '</div>';
            col += '</div>';
            col += '</div>';
            col += '</div>';
            $('#row_items').append(col);
        }

        function plus_item(key) {
            var plus = document.getElementById('plus-' + key);
            var jumlah_barang = document.getElementById('jumlah-barang-' + key);
            if (jumlah_barang.value < 100) {
                jumlah_barang.value = parseInt(jumlah_barang.value) + 1;
            }
        }

        function minus_item(key) {
            var minus = document.getElementById('minus-' + key);
            var jumlah_barang = document.getElementById('jumlah-barang-' + key);
            if (jumlah_barang.value > 1) {
                jumlah_barang.value = parseInt(jumlah_barang.value) - 1;
            }
            console.log(jumlah_barang.value);
        }

        function delete_item(key) {
            $('#col_item-' + key).remove();
            console.log(key);
            item_id = item_id.filter(item => item !== key);
            console.log(item_id);
        }
    </script>
@endsection
