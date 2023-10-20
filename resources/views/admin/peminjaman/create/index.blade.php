@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/peminjaman') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Peminjam</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="selectgroup">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="check" id="check" value="0"
                                                class="selectgroup-input" onclick="click_radio()"
                                                {{ old('check', '0') == '0' ? 'checked' : '' }}>
                                            <span class="selectgroup-button">Buat Baru</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="check" id="check" value="1"
                                                class="selectgroup-input" onclick="click_radio()"
                                                {{ old('check') == '1' ? 'checked' : '' }}>
                                            <span class="selectgroup-button">Sudah Ada</span>
                                        </label>
                                    </div>
                                </div>
                                <div id="layout_0">
                                    <div class="form-group">
                                        <label for="nama">Nama Tamu</label>
                                        <input type="text" name="nama" id="nama" class="form-control"
                                            onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))"
                                            value="{{ old('nama') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="institusi">
                                            Asal Institusi
                                            <br>
                                            <small>(contoh: Universitas ABC, Rumah Sakit ABC)</small>
                                        </label>
                                        <input type="text" name="institusi" id="institusi" class="form-control"
                                            value="{{ old('institusi') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="telp">Nomor Telepon
                                            <br>
                                            <small>(contoh: 081234567890)</small>
                                        </label>
                                        <input type="tel" class="form-control" name="telp" id="telp"
                                            value="{{ old('telp') }}"
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat">Alamat Tamu
                                            <small>(opsional)</small>
                                        </label>
                                        <textarea name="alamat" id="alamat" cols="30" rows="10" class="form-control" style="height: 80px">{{ old('alamat') }}</textarea>
                                    </div>
                                </div>
                                <div id="layout_1">
                                    <div class="form-group">
                                        <label for="tamu_id">Pilih Tamu</label>
                                        <select class="form-control select2" name="tamu_id" id="tamu_id">
                                            @foreach ($tamus as $tamu)
                                                <option value="{{ $tamu->id }}" {{ old('tamu_id') == $tamu->id }}>
                                                    {{ $tamu->institusi }} - {{ $tamu->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
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
                        <div class="card">
                            <div class="card-header">
                                <h4>Daftar Barang</h4>
                                <div class="card-header-action">
                                    <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#modalBarang">Pilih
                                        Alat</button>
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
                    </div>
                </div>
            </form>
        </div>
    </section>
    <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarang"
        aria-hidden="true">
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
                        @foreach ($barangs as $barang)
                            <div class="card border rounded shadow-sm mb-2">
                                <div class="card-body d-flex align-center justify-content-between p-0">
                                    <div class="py-2 px-3 m-0">
                                        <strong>{{ $barang->nama }}</strong>
                                    </div>
                                    <div class=" py-2 px-3 m-0">
                                        <button class="btn btn-primary btn-sm float-right"
                                            onclick="add_item({{ $barang->id }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var radioButtons = document.querySelectorAll('input[name="check"]');
        var selectedValue = "1";
        for (const radioButton of radioButtons) {
            if (radioButton.checked) {
                selectedValue = radioButton.value;
                break;
            }
        }
        if (selectedValue == '0') {
            layout_0.style.display = "inline";
            layout_1.style.display = "none";
        } else if (selectedValue == '1') {
            layout_0.style.display = "none";
            layout_1.style.display = "inline";
        }

        function click_radio() {
            for (const radioButton of radioButtons) {
                if (radioButton.checked) {
                    selectedValue = radioButton.value;
                    break;
                }
            }
            if (selectedValue == '0') {
                layout_0.style.display = "inline";
                layout_1.style.display = "none";
            } else if (selectedValue == '1') {
                layout_0.style.display = "none";
                layout_1.style.display = "inline";
            }
        }
        let modal_card_barang = document.getElementById('modal_card_barang');
        let modal_card_barang_kosong = document.getElementById('modal_card_barang_kosong');

        function search_handler(event) {
            if (event.key === "Enter") {
                search_item();
            }
        }

        function search_item() {
            let keyword = document.getElementById('keyword').value;
            $('#modal_card_barang').empty();
            $.ajax({
                url: "{{ url('peminjam/peminjaman/search_items') }}",
                type: "GET",
                data: {
                    "keyword": keyword
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        modal_card_barang.style.display = 'inline';
                        modal_card_barang_kosong.style.display = 'none';
                        $.each(data, function(key, value) {
                            modal_items(value);
                        });
                    } else {
                        modal_card_barang.style.display = 'none';
                        modal_card_barang_kosong.style.display = 'inline';
                    }
                },
            });
        }

        function modal_items(data) {
            var card_items = '<div class="card border rounded shadow-sm mb-2">';
            card_items += '<div class="card-body d-flex align-center justify-content-between p-0">';
            card_items += '<div class="py-2 px-3 m-0">';
            card_items += '<strong>' + data.nama + '</strong>';
            card_items += '</div>';
            card_items += '<div class=" py-2 px-3 m-0">';
            card_items += '<button class="btn btn-primary btn-sm float-right" onclick="add_item(' + data.id +
                ')">Pilih</button>';
            card_items += '</div>';
            card_items += '</div>';
            card_items += '</div>';
            $('#modal_card_barang').append(card_items);
        }

        var item_id = [];

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

        var data_items = @json(session('data_items'));
        if (data_items != null) {
            $('#card_barang_kosong').hide();
            $('row_items').empty();
            $.each(data_items, function(key, value) {
                item_id.push(value.id);
                set_items(key, value, true);
            });
        } else {
            console.log('empty');
        }

        function set_items(key, data, is_session = false) {
            var total = 1;
            if (is_session) {
                total = data.total;
            }
            var col = '<div class="col-12 col-md-6 col-lg-4" id="col_item-' + data.id + '">';
            col += '<div class="card mb-3">';
            col += '<div class="card-body">';
            col += '<p class="mb-1">';
            col += '<strong>' + data.nama + '</strong>'
            col += '</p>';
            col += '<p class="mb-1">Jumlah</p>';
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
