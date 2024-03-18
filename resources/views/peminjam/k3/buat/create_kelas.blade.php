@extends('layouts.app')

@section('title', 'Buat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/k3/buat') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Buat Peminjaman</h1>
        </div>
        <div class="section-body">
            <form action="{{ url('peminjam/k3/buat') }}" method="POST" autocomplete="off">
                @csrf
                @if (session('error_peminjaman'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <div class="alert-title">GAGAL !</div>
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <ul class="px-3 mb-0">
                                @foreach (session('error_peminjaman') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>Detail Peminjaman</h4>
                    </div>
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Kategori Praktik</label>
                            <input type="text" class="form-control" value="{{ $praktik->nama }}" readonly>
                            <input type="hidden" name="praktik_id" class="form-control" value="{{ $praktik->id }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal">Waktu Praktik</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="jam">Jam Praktik</label>
                            <select class="form-control selectric" name="jam" id="jam" onchange="custom_jam()">
                                <option value="">- Pilih -</option>
                                <option value="08.00-09.40" {{ old('jam') == '08.00-09.40' ? 'selected' : '' }}>
                                    08.00-09.40
                                </option>
                                <option value="09.40-11.20" {{ old('jam') == '09.40-11.20' ? 'selected' : '' }}>
                                    09.40-11.20
                                </option>
                                <option value="12.30-14.10" {{ old('jam') == '12.30-14.10' ? 'selected' : '' }}>
                                    12.30-14.10
                                </option>
                                <option value="14.10-15.40" {{ old('jam') == '14.10-15.40' ? 'selected' : '' }}>
                                    14.10-15.40
                                </option>
                                <option value="lainnya" {{ old('jam') == 'lainnya' ? 'selected' : '' }}>
                                    Jam Lainnya
                                </option>
                            </select>
                        </div>
                        <div id="layout_custom_jam">
                            <div class="form-group mb-3">
                                <label for="jam_awal">Jam Awal</label>
                                <input type="time" name="jam_awal" id="jam_awal" class="form-control"
                                    value="{{ old('jam_awal') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="jam_akhir">Jam Akhir</label>
                                <input type="time" name="jam_akhir" id="jam_akhir" class="form-control"
                                    value="{{ old('jam_akhir') }}">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="matakuliah">Mata Kuliah</label>
                            <input type="text" name="matakuliah" id="matakuliah" class="form-control"
                                value="{{ old('matakuliah') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="praktik">Praktik</label>
                            <input type="text" name="praktik" id="praktik" class="form-control"
                                value="{{ old('praktik') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="dosen">Dosen Pengampu</label>
                            <input type="text" name="dosen" id="dosen" class="form-control"
                                value="{{ old('dosen') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="kelas">Tingkat Kelas</label>
                            <input type="text" name="kelas" id="kelas" class="form-control"
                                value="{{ old('kelas') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan">Ruang Kelas</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control"
                                value="{{ old('keterangan') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="laboran_id">Laboran Penerima</label>
                            <select class="custom-select custom-select-sm" id="laboran_id" name="laboran_id">
                                <option value="">- Pilih -</option>
                                @foreach ($laborans as $laboran)
                                    <option value="{{ $laboran->id }}"
                                        {{ old('laboran_id') == $laboran->id ? 'selected' : '' }}>
                                        {{ ucfirst($laboran->ruangs()->orderBy('id', 'desc')->first()->prodi->singkatan) }}
                                        -
                                        {{ $laboran->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @if (session('error_anggota'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <div class="alert-title">GAGAL !</div>
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <ul class="px-3 mb-0">
                                @foreach (session('error_anggota') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="card mb-0">
                    <div class="card-header">
                        <h4>Peminjam</h4>
                    </div>
                    <div class="card-body pb-0">
                        <div id="layout_ketua">
                            <div class="form-group mb-3">
                                <label for="ketua">Ketua</label>
                                <input type="text" class="form-control"
                                    value="{{ auth()->user()->kode }} | {{ auth()->user()->nama }}" readonly>
                            </div>
                        </div>
                        <div id="layout_anggota">
                            <div class="form-group mb-3">
                                <label for="anggota">Anggota</label>
                                <br>
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#modal-anggota">
                                    Masukan Anggota
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body p-0">
                        <table class="table table-md table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>Nama</th>
                                    <th class="text-center" style="width: 60px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="table_anggota">
                                <tr id="table_anggota_empty">
                                    <td class="text-center" colspan="3">Masukan Anggota</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (session('error_barang'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <div class="alert-title">GAGAL !</div>
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <ul class="px-3 mb-0">
                                @foreach (session('error_barang') as $error)
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
                            <button type="button" class="btn btn-info" data-toggle="modal"
                                data-target="#modal-barang">Pilih</button>
                        </div>
                    </div>
                </div>
                <div class="card mb-3" id="card_barang_kosong">
                    <div class="card-body p-5 text-center">
                        <span>- Belum ada barang yang di tambahkan -</span>
                    </div>
                </div>
                <div class="row" id="row_items">
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>Tambah Bahan</h4>
                        <small>(opsional)</small>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <textarea class="form-control" id="bahan" name="bahan" style="height: 120px"
                                placeholder="masukan bahan yang dibutuhkan">{{ old('bahan') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Buat Peminjaman</button>
                </div>
            </form>
        </div>
    </section>
    <div class="modal fade" id="modal-anggota" role="dialog" aria-labelledby="modal-anggota" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Mahasiswa</h5>
                </div>
                <div class="modal-header pt-0 pb-3 border-bottom shadow-sm">
                    <div class="input-group">
                        <input type="search" class="form-control" id="keyword-anggota" autocomplete="off"
                            placeholder="Cari NIM / Nama">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" onclick="search_anggota()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="modal_card_anggota">
                        @foreach ($peminjams as $peminjam)
                            <div class="card border rounded shadow-sm mb-2">
                                <label for="checkbox_anggota-{{ $peminjam->id }}"
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">
                                    <span>
                                        {{ $peminjam->kode }} |
                                        <strong>{{ $peminjam->nama }}</strong>
                                    </span>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                            id="checkbox_anggota-{{ $peminjam->id }}"
                                            onclick="add_anggota({{ $peminjam->id }})">
                                        <label for="checkbox_anggota-{{ $peminjam->id }}"
                                            class="custom-control-label"></label>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal_card_anggota_empty" style="display: none">
                        <div class="card border rounded shadow-sm mb-2">
                            <div class="card-body p-0">
                                <p class="py-2 px-3 m-0 text-center text-muted">- Mahasiswa tidak ditemukan -</p>
                            </div>
                        </div>
                    </div>
                    <div id="modal_card_anggota_limit">
                        <div class="card border rounded shadow-sm mb-0 mt-3">
                            <div class="card-body p-0">
                                <p class="py-2 px-3 m-0 text-center text-muted">Cari dengan <strong>kata kunci</strong>
                                    lebih detail</p>
                            </div>
                        </div>
                    </div>
                    <div id="modal_card_anggota_loading" style="display: none">
                        <div class="card border rounded shadow-sm mb-0">
                            <div class="card-body p-0">
                                <p class="py-2 px-3 m-0 text-center text-muted">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-barang" tabindex="-1" role="dialog" aria-labelledby="modal-barang"
        aria-hidden="true">
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
                            <div class="card border rounded shadow-sm mb-2">
                                <label for="checkbox-{{ $barang->id }}"
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">
                                    <span>
                                        <strong>{{ $barang->nama }}</strong><br>
                                        <small>({{ $barang->ruang->nama }})</small>
                                    </span>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                            id="checkbox-{{ $barang->id }}" onclick="add_item({{ $barang->id }})">
                                        <label for="checkbox-{{ $barang->id }}" class="custom-control-label"></label>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal_card_barang_empty" style="display: none;">
                        <div class="card border rounded shadow-sm mb-2">
                            <div class="card-body p-0">
                                <p class="py-2 px-3 m-0 text-center text-muted">- Barang tidak ditemukan -</p>
                            </div>
                        </div>
                    </div>
                    <div id="modal_card_barang_limit">
                        <div class="card border rounded shadow-sm mb-0 mt-3">
                            <div class="card-body p-0">
                                <p class="py-2 px-3 m-0 text-center text-muted">Cari dengan <strong>kata kunci</strong>
                                    lebih detail</p>
                            </div>
                        </div>
                    </div>
                    <div id="modal_card_barang_loading" style="display: none">
                        <div class="card border rounded shadow-sm mb-0">
                            <div class="card-body p-0">
                                <p class="py-2 px-3 m-0 text-center text-muted">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        custom_jam();

        function custom_jam() {
            if ($('#jam').val() == 'lainnya') {
                $('#layout_custom_jam').show();
            } else {
                $('#layout_custom_jam').hide();
            }
        }

        $('#keyword').on('search', function() {
            search_item();
        });

        $('#keyword-anggota').on('search', function() {
            search_anggota();
        });

        var anggota_id = [];

        var data_anggotas = @json(session('data_anggotas'));
        if (data_anggotas !== null) {
            if (data_anggotas.length > 0) {
                $('#table_anggota_empty').hide();
                $('table_anggota').empty();
                var urutan = 0;
                $.each(data_anggotas, function(key, value) {
                    anggota_id.push(value.id);
                    var urutan = anggota_id.length;
                    set_anggotas(urutan, value, true);
                });
            }
        } else {
            $('#table_anggota_empty').show();
        }

        function search_anggota() {
            $('#modal_card_anggota').empty();
            $('#modal_card_anggota_limit').hide();
            $('#modal_card_anggota_empty').hide();
            $('#modal_card_anggota_loading').show();
            $.ajax({
                url: "{{ url('peminjam/search_anggotas') }}",
                type: "GET",
                data: {
                    "keyword": $('#keyword-anggota').val()
                },
                dataType: "json",
                success: function(data) {
                    $('#modal_card_anggota_loading').hide();
                    if (data.length > 0) {
                        $('#modal_card_anggota').show();
                        $('#modal_card_anggota_empty').hide();
                        $.each(data, function(key, value) {
                            console.log(anggota_id.includes(value.id));
                            modal_anggotas(value, anggota_id.includes(value.id));
                        });
                        if (data.length == 10) {
                            $('#modal_card_anggota_limit').show();
                        }
                    } else {
                        $('#modal_card_anggota').hide();
                        $('#modal_card_anggota_limit').hide();
                        $('#modal_card_anggota_empty').show();
                    }
                },
            });
        }

        function modal_anggotas(data, is_selected) {
            if (is_selected) {
                var checked = 'checked';
            } else {
                var checked = '';
            }

            var card_anggota = '<div class="card border rounded shadow-sm mb-2">';
            card_anggota +=
                '<label for="checkbox_anggota-' + data.id +
                '" class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">';
            card_anggota += '<span> ' + data.kode + ' | <strong>' + data.nama + '</strong></span>';
            card_anggota += '<div class="custom-checkbox custom-control">';
            card_anggota +=
                '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox_anggota-' + data
                .id + '" onclick="add_anggota(' + data.id + ')" ' + checked + '>';
            card_anggota += '<label for="checkbox_anggota-' + data.id + '"';
            card_anggota += 'class="custom-control-label"></label>';
            card_anggota += '</div>';
            card_anggota += '</label>';
            card_anggota += '</div>';

            $('#modal_card_anggota').append(card_anggota);
        }

        function add_anggota(id) {
            var checkbox = document.getElementById('checkbox_anggota-' + id);
            if (checkbox.checked) {
                if (!anggota_id.includes(id)) {
                    anggota_id.push(id);
                    $.ajax({
                        url: "{{ url('peminjam/add_anggota') }}" + '/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            var urutan = anggota_id.length;
                            set_anggotas(urutan, data);
                        },
                    });
                }
                if (anggota_id.length > 0) {
                    $('#table_anggota_empty').hide();
                }
            } else {
                delete_anggota(id);
            }
        }

        function set_anggotas(urutan, data, is_session = false) {
            if (is_session) {
                $('#checkbox_anggota-' + data.id).prop('checked', true);
            }
            var col = '<tr id="table_anggota_tr-' + data.id + '">';
            col += '<td class="text-center" id="urutan">' + urutan + '</td>';
            col += '<td> ' + data.kode + ' | <strong>' + data.nama + '</strong></td>';
            col += '<td class="text-center">';
            col += '<input type="hidden" name="anggotas[' + data.id + ']" value="' + data.kode + '">';
            col += '<button class="btn btn-danger btn-sm" onclick="delete_anggota(' + data.id + ')">';
            col += '<i class="fas fa-trash"></i>';
            col += '</button>';
            col += '</td>';
            col += '</tr>';

            $('#table_anggota').append(col);
        }

        function delete_anggota(key) {
            $('#table_anggota_tr-' + key).remove();
            anggota_id = anggota_id.filter(item => item !== key);

            document.getElementById('checkbox_anggota-' + key).checked = false;
            if (anggota_id.length == 0) {
                $('#table_anggota_empty').show();
            } else {
                var urutan = document.querySelectorAll('#urutan');
                for (let i = 0; i < urutan.length; i++) {
                    urutan[i].innerText = i + 1;
                }
            }
        }

        var item_id = [];

        var data_items = @json(session('data_items'));
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
            $('#card_barang_kosong').show();
        }

        function search_item() {
            $('#modal_card_barang').empty();
            $('#modal_card_barang_limit').hide();
            $('#modal_card_barang_empty').hide();
            $('#modal_card_barang_loading').show();
            $.ajax({
                url: "{{ url('peminjam/search_items') }}",
                type: "GET",
                data: {
                    "keyword": $('#keyword').val()
                },
                dataType: "json",
                success: function(data) {
                    $('#modal_card_barang_loading').hide();
                    if (data.length > 0) {
                        $('#modal_card_barang').show();
                        $('#modal_card_barang_empty').hide();
                        $.each(data, function(key, value) {
                            modal_items(value, item_id.includes(value.id));
                        });
                        if (data.length == 10) {
                            $('#modal_card_barang_limit').show();
                        }
                    } else {
                        $('#modal_card_barang').hide();
                        $('#modal_card_barang_limit').hide();
                        $('#modal_card_barang_empty').show();
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
            card_items += '<small>(' + data.ruang.nama + ')</small>';
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
            var checkbox = document.getElementById('checkbox-' + id);
            if (checkbox.checked) {
                if (!item_id.includes(id)) {
                    var key = item_id.length;
                    $.ajax({
                        url: "{{ url('peminjam/add_item') }}" + '/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            set_items(key, data);
                        },
                    });
                    item_id.push(id);
                }
                if (item_id.length > 0) {
                    $('#card_barang_kosong').hide();
                }
            } else {
                delete_item(id);
            }
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
            col += '<small>(' + data.ruang.nama + ')</small>';
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
        }

        function delete_item(key) {
            $('#col_item-' + key).remove();
            item_id = item_id.filter(item => item !== key);

            document.getElementById('checkbox-' + key).checked = false;
            if (item_id.length == 0) {
                $('#card_barang_kosong').show();
            }
        }
    </script>
@endsection
