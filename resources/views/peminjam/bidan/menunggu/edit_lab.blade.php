@extends('layouts.app')

@section('title', 'Peminjaman Menunggu')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/bidan/menunggu') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Menunggu</h1>
        </div>
        @php
            if (!is_null($data)) {
                $error_barang = $data['error_barang'];
                $data_items = $data['data_items'];
                $detail_pinjam_data = [];
            } else {
                $error_barang = null;
                $data_items = null;
                $detail_pinjam_data = [];
                foreach ($detail_pinjams as $detail_pinjam) {
                    $detail_pinjam_data[$detail_pinjam->id] = $detail_pinjam->detail_pinjam_id;
                }
            }
        @endphp
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Peminjaman</h4>
                    <div class="card-header-action">
                        <span class="badge badge-warning">Menunggu</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->praktik_nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Waktu</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->jam_awal }} - {{ $pinjam->jam_akhir }},
                                    {{ date('d M Y', strtotime($pinjam->tanggal_awal)) }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Ruang (Lab)</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->ruang_nama }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Laboran</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->laboran_nama }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Mata Kuliah</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->matakuliah }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Praktik</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->praktik }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Dosen</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $pinjam->dosen }}
                                </div>
                            </div>
                            <div class="row mb-3">
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
            <div class="card">
                <div class="card-header">
                    <h4>Detail Kelompok</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row mb-3">
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
            <form action="{{ url('peminjam/bidan/menunggu/' . $pinjam->id) }}" method="POST" autocomplete="off">
                @csrf
                @method('put')
                @if ($error_barang)
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <div class="alert-title">GAGAL !</div>
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <ul class="px-3 mb-0">
                                @foreach ($error_barang as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>Daftar Barang</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalBarang">Pilih
                                Barang</button>
                        </div>
                    </div>
                </div>
                <div class="card" id="card_barang_kosong">
                    <div class="card-body p-5 text-center">
                        <span class="text-muted">- Belum ada barang yang di tambahkan -</span>
                    </div>
                </div>
                <div class="row" id="row_items">
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Detail Bahan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <strong>Bahan</strong>
                            </div>
                            <div class="col-md-10">
                                {{ $pinjam->bahan }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="float-right">
                    <button type="submit" class="btn btn-primary">Update Pinjaman</button>
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
                        <input type="search" class="form-control" id="keyword" autocomplete="off"
                            placeholder="Cari barang">
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
                            @php
                                if (in_array($barang->id, array_keys($detail_pinjam_data))) {
                                    $detail_pinjam_id = $detail_pinjam_data[$barang->id];
                                } else {
                                    $detail_pinjam_id = 0;
                                }
                            @endphp
                            <div class="card border rounded shadow-sm mb-2">
                                <label for="checkbox-{{ $barang->id }}"
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 mb-0 px-3">
                                    <span>
                                        <strong>{{ $barang->nama }}</strong><br>
                                        <span>({{ $barang->ruang->nama }})</span>
                                    </span>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                            id="checkbox-{{ $barang->id }}"
                                            onclick="add_item({{ $barang->id }}, {{ $detail_pinjam_id }})">
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
                <div class="modal-footer border-top shadow-sm">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#keyword').on('search', function() {
            search_item();
        });

        var item_id = [];
        var data_items = @json($data_items);

        if (data_items !== null) {
            if (data_items.length > 0) {
                $('#card_barang_kosong').hide();
                $('#row_items').empty();
                $.each(data_items, function(key, value) {
                    item_id.push(value.id);
                    set_items(key, value, true);
                });
            }
        } else {
            data_items = @json($detail_pinjams);
            if (data_items.length > 0) {
                $('#card_barang_kosong').hide();
                $('#row_items').empty();
                $.each(data_items, function(key, value) {
                    item_id.push(value.id);
                    set_items(key, value, true);
                });
            } else {
                $('#card_barang_kosong').show();
            }
        }

        function search_item() {
            $('#modal_card_barang').empty();
            $.ajax({
                url: "{{ url('peminjam/peminjaman/search_items') }}",
                type: "GET",
                data: {
                    "keyword": $('#keyword').val()
                },
                dataType: "json",
                success: function(data) {
                    if (data.length > 0) {
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

        function add_item(id, detail_pinjam_id) {
            var checkbox = document.getElementById('checkbox-' + id);
            if (checkbox.checked) {
                if (!item_id.includes(id)) {
                    var key = item_id.length;
                    $.ajax({
                        url: "{{ url('peminjam/peminjaman/add_item') }}" + '/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            set_items(key, data);
                        },
                    });
                    item_id.push(id);
                }
            } else {
                delete_item(id, detail_pinjam_id);
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
            col += '<span>(' + data.ruang_nama + ')</span>';
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
            col += '<button type="button" class="btn btn-danger" onclick="delete_item(' + data.id + ', ' + data
                .detail_pinjam_id + ')">';
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
        }

        function delete_item(key, detail_pinjam_id) {
            $('#col_item-' + key).remove();
            item_id = item_id.filter(item => item !== key);

            $('#checkbox-' + key).prop('checked', false);
            if (item_id.length == 0) {
                $('#card_barang_kosong').show();
            }

            if (detail_pinjam_id != 0) {
                $.ajax({
                    url: "{{ url('peminjam/peminjaman/delete_item') }}" + '/' + detail_pinjam_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#checkbox-' + key).attr('onclick', 'add_item(' + key + ', 0)');
                    },
                });
            }
        }
    </script>
@endsection
