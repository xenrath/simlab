@extends('layouts.app')

@section('title', 'Peminjaman Laboratorium')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/normal/peminjaman-new') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Peminjaman Laboratorium</h1>
        </div>
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
        <div class="section-body">
            <form action="{{ url('peminjam/normal/peminjaman-new/laboratorium') }}" method="POST" autocomplete="off"
                id="form-submit">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Detail Peminjaman</h4>
                    </div>
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tanggal">Waktu Praktik</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}">
                        </div>
                        <div class="form-group">
                            <label for="jam">Jam Praktik</label>
                            <select class="form-control selectric" name="jam" id="jam" onchange="customJam()">
                                <option value="">- Pilih -</option>
                                <option value="08.00-09.40" {{ old('jam') == '08.00-09.40' ? 'selected' : '' }}>
                                    08.00-09.40</option>
                                <option value="09.40-11.20" {{ old('jam') == '09.40-11.20' ? 'selected' : '' }}>
                                    09.40-11.20</option>
                                <option value="12.30-14.10" {{ old('jam') == '12.30-14.10' ? 'selected' : '' }}>
                                    12.30-14.10</option>
                                <option value="14.10-15.40" {{ old('jam') == '14.10-15.40' ? 'selected' : '' }}>
                                    14.10-15.40</option>
                                <option value="lainnya" {{ old('jam') == 'lainnya' ? 'selected' : '' }}>Jam Lainnya
                                </option>
                            </select>
                        </div>
                        <div id="layout_custom_jam">
                            <div class="form-group">
                                <label for="jam_awal">Jam Awal</label>
                                <input type="time" name="jam_awal" id="jam_awal" class="form-control"
                                    value="{{ old('jam_awal') }}">
                            </div>
                            <div class="form-group">
                                <label for="jam_akhir">Jam Akhir</label>
                                <input type="time" name="jam_akhir" id="jam_akhir" class="form-control"
                                    value="{{ old('jam_akhir') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ruang_id">Ruang Lab</label>
                            <select class="form-control select2" id="ruang_id" name="ruang_id">
                                <option value="">- Pilih -</option>
                                @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}"
                                        {{ old('ruang_id') == $ruang->id ? 'selected' : '' }}>
                                        {{ $ruang->nama }} ({{ ucfirst($ruang->singkatan) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="matakuliah">Mata Kuliah</label>
                            <input type="text" name="matakuliah" id="matakuliah" class="form-control"
                                value="{{ old('matakuliah') }}">
                        </div>
                        <div class="form-group">
                            <label for="praktik">Praktik</label>
                            <input type="text" name="praktik" id="praktik" class="form-control"
                                value="{{ old('praktik') }}">
                        </div>
                        <div class="form-group">
                            <label for="dosen">Dosen Pengampu</label>
                            <input type="text" name="dosen" id="dosen" class="form-control"
                                value="{{ old('dosen') }}">
                        </div>
                        <div class="form-group">
                            <label for="kelas">Tingkat Kelas</label>
                            <input type="text" name="kelas" id="kelas" class="form-control"
                                value="{{ old('kelas') }}">
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
                            <div class="form-group">
                                <label for="ketua">Ketua</label>
                                <input type="text" class="form-control"
                                    value="{{ auth()->user()->kode }} | {{ auth()->user()->nama }}" readonly>
                            </div>
                        </div>
                        <div id="layout_anggota">
                            <div class="form-group">
                                <label for="anggota">Anggota</label>
                                <br>
                                <button type="button" class="btn btn-warning" data-toggle="modal"
                                    data-target="#modalAnggota">
                                    Masukan Anggota
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
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
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Bahan</h4>
                        <small>(opsional)</small>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control" id="bahan" name="bahan" style="height: 120px"
                                placeholder="masukan bahan yang dibutuhkan"></textarea>
                        </div>
                    </div>
                </div>
                <div class="float-right">
                    <button type="submit" class="btn btn-primary">Buat Pinjaman</button>
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
                            onkeypress="search_handler(event, 'item')" placeholder="Cari barang">
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
                            if (session('item_id')) {
                                foreach (session('item_id') as $i) {
                                    array_push($item_id, $i);
                                }
                            }
                        @endphp
                        @foreach ($barangs as $barang)
                            <div class="card border rounded shadow-sm mb-2">
                                <div
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3">
                                    <strong>{{ $barang->nama }}</strong>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                            id="checkbox-{{ $barang->id }}" onclick="add_item({{ $barang->id }})"
                                            {{ in_array($barang->id, $item_id) ? 'checked' : '' }}>
                                        <label for="checkbox-{{ $barang->id }}" class="custom-control-label"></label>
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
                <div class="modal-footer border-top shadow-sm">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAnggota" role="dialog" aria-labelledby="modalAnggota" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Mahasiswa</h5>
                </div>
                <div class="modal-header pt-0 pb-3 border-bottom shadow-sm">
                    <div class="input-group">
                        <input type="text" class="form-control" id="keyword-anggota" autocomplete="off"
                            onkeypress="search_handler(event, 'anggota')" placeholder="Cari NIM / Nama">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" onclick="search_anggota()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="modal_card_anggota">
                        @php
                            $id_anggotas = [];
                            if (session('data_anggotas')) {
                                foreach (session('data_anggotas') as $data_anggota) {
                                    array_push($id_anggotas, $data_anggota['id']);
                                }
                            }
                        @endphp
                        @foreach ($peminjams as $peminjam)
                            <div class="card border rounded shadow-sm mb-2">
                                <div
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3">
                                    <span>
                                        {{ $peminjam->kode }} |
                                        <strong>{{ $peminjam->nama }}</strong>
                                    </span>
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                            id="checkbox_anggota-{{ $peminjam->id }}"
                                            onclick="add_anggota({{ $peminjam->id }})"
                                            {{ in_array($peminjam->id, $id_anggotas) ? 'checked' : '' }}>
                                        <label for="checkbox_anggota-{{ $peminjam->id }}"
                                            class="custom-control-label"></label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal_card_anggota_empty">
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
        $('#layout_custom_jam').hide();

        function customJam() {
            if ($('#jam').val() == 'lainnya') {
                $('#layout_custom_jam').show();
            } else {
                $('#layout_custom_jam').hide();
            }
        }

        var tanggalAwal = document.getElementById('tanggal_awal');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
        var jamAwal = document.getElementById('jam_awal');
        var jamAkhir = document.getElementById('jam_ahir');
        var today = "{{ Carbon\Carbon::now()->format('Y-m-d') }}";

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
                    set_anggotas(urutan, value);
                });
            }
        } else {
            $('#table_anggota_empty').show();
        }

        function search_handler(event, type) {
            if (event.key === "Enter") {
                if (type == 'anggota') {
                    search_anggota();
                } else if (type == 'item') {
                    search_item();
                }
            }
        }

        function search_anggota() {
            let keyword = document.getElementById('keyword-anggota').value;
            $('#modal_card_anggota').empty();
            $.ajax({
                url: "{{ url('peminjam/peminjaman/search_anggotas') }}",
                type: "GET",
                data: {
                    "keyword": keyword
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $('#modal_card_anggota').show();
                        $('#modal_card_anggota_empty').hide();
                        $.each(data, function(key, value) {
                            console.log(anggota_id.includes(value.id));
                            modal_anggotas(value, anggota_id.includes(value.id));
                        });
                    } else {
                        $('#modal_card_anggota').hide();
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
                '<div class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3">';
            card_anggota += '<span> ' + data.kode + ' | <strong>' + data.nama + '</strong></span>';
            card_anggota += '<div class="custom-checkbox custom-control">';
            card_anggota +=
                '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox_anggota-' + data
                .id + '" onclick="add_anggota(' + data.id + ')" ' + checked + '>';
            card_anggota += '<label for="checkbox_anggota-' + data.id + '"';
            card_anggota += 'class="custom-control-label"></label>';
            card_anggota += '</div>';
            card_anggota += '</div>';
            card_anggota += '</div>';

            $('#modal_card_anggota').append(card_anggota);
        }

        function add_anggota(id) {
            var checkbox = document.getElementById('checkbox_anggota-' + id);
            if (checkbox.checked) {
                if (!anggota_id.includes(id)) {
                    anggota_id.push(id);
                    $.ajax({
                        url: "{{ url('peminjam/peminjaman/add_anggota') }}" + '/' + id,
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

        function set_anggotas(urutan, data) {
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
                $('row_items').empty();
                $.each(data_items, function(key, value) {
                    item_id.push(value.id);
                    set_items(key, value, true);
                });
            }
        } else {
            $('#card_barang_kosong').show();
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
                            modal_items(value, item_id.includes(value.id));
                        });
                    } else {
                        modal_card_barang.style.display = 'none';
                        modal_card_barang_kosong.style.display = 'inline';
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
                '<div class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3">';
            card_items += '<strong>' + data.nama + '</strong>';
            card_items += '<div class="custom-checkbox custom-control">';
            card_items +=
                '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-' + data.id +
                '" onclick="add_item(' + data.id + ')" ' + checked + ' >';
            card_items += '<label for="checkbox-' + data.id + '" class="custom-control-label"></label>';
            card_items += '</div>';
            card_items += '</div>';
            card_items += '</div>';

            $('#modal_card_barang').append(card_items);
        }

        function add_item(id) {
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
                delete_item(id);
            }

            $('#card_barang_kosong').hide();
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
        }

        function delete_item(key) {
            $('#col_item-' + key).remove();
            item_id = item_id.filter(item => item !== key);

            document.getElementById('checkbox-' + key).checked = false;
            if (item_id.length == 0) {
                $('#card_barang_kosong').show();
            }
        }

        function data_item(no, data) {
            var value = "1";
            if (item != null) {
                for (let i = 0; i < jumlah.length; i++) {
                    const element = jumlah[i];
                    if (element['barang_id'] == data.id) {
                        value = element['jumlah'];
                        console.log(value);
                    }
                }
            }
            var data_item = "<tr>";
            data_item += "<td class='text-center'>" + no + "</td>";
            data_item += "<td>" + data.nama + "</td>";
            data_item += "<td>" + data.normal + " " + data.satuan.singkatan + "</td>";
            data_item += "<td>";
            data_item += "<div class='input-group'>";
            data_item += "<input class='form-control' type='number' name='jumlah[" +
                data.id +
                "]' oninput='this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= " +
                data.normal + " ? Math.abs(this.value) : null' value=" + value + ">";
            data_item += "<input type='hidden' name='barang_id[" + data.id + "]' value='" + data.id +
                "' class='form-control'>";
            data_item += "</div>";
            data_item += "</td>";
            data_item += "</tr>";
            $("#dataItems").append(data_item);
        }
    </script>
@endsection
