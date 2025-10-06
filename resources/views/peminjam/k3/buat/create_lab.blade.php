@extends('layouts.app')

@section('title', 'Praktik Laboratorium')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/k3/buat') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Praktik Laboratorium</h1>
        </div>
        <div class="section-body">
            <form action="{{ url('peminjam/k3/buat/praktik-laboratorium') }}" method="POST" autocomplete="off"
                id="form-submit">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="card rounded-0 mb-3">
                            <div class="card-header">
                                <h4>Form Peminjaman</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-2">
                                    <label for="tanggal">Waktu Praktik</label>
                                    <input type="date"
                                        class="form-control rounded-0 @error('tanggal') is-invalid @enderror" id="tanggal"
                                        name="tanggal" min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}">
                                    @error('tanggal')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label for="jam">Jam Praktik</label>
                                    <select class="form-control rounded-0 @error('jam') is-invalid @enderror" name="jam"
                                        id="jam" onchange="custom_jam()">
                                        <option value="">Pilih</option>
                                        <option value="08.00-09.40" {{ old('jam') == '08.00-09.40' ? 'selected' : '' }}>
                                            08.00-09.40</option>
                                        <option value="09.40-11.20" {{ old('jam') == '09.40-11.20' ? 'selected' : '' }}>
                                            09.40-11.20</option>
                                        <option value="12.30-14.10" {{ old('jam') == '12.30-14.10' ? 'selected' : '' }}>
                                            12.30-14.10</option>
                                        <option value="14.10-15.40" {{ old('jam') == '14.10-15.40' ? 'selected' : '' }}>
                                            14.10-15.40</option>
                                        <option value="lainnya" {{ old('jam') == 'lainnya' ? 'selected' : '' }}>Jam lainnya
                                        </option>
                                    </select>
                                    @error('jam')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div id="layout_custom_jam">
                                    <div class="form-group mb-2">
                                        <label for="jam_awal">Jam Awal</label>
                                        <input type="time" name="jam_awal" id="jam_awal"
                                            class="form-control rounded-0 @error('jam_awal') is-invalid @enderror"
                                            value="{{ old('jam_awal') }}">
                                        @error('jam_awal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="jam_akhir">Jam Akhir</label>
                                        <input type="time" name="jam_akhir" id="jam_akhir"
                                            class="form-control rounded-0  @error('jam_akhir') is-invalid @enderror"
                                            value="{{ old('jam_akhir') }}">
                                        @error('jam_akhir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="ruang_id">Ruang Lab</label>
                                    <select class="form-control rounded-0 @error('ruang_id') is-invalid @enderror"
                                        id="ruang_id" name="ruang_id">
                                        <option value="">- Pilih -</option>
                                        @foreach ($ruangs as $ruang)
                                            <option value="{{ $ruang->id }}"
                                                {{ old('ruang_id') == $ruang->id ? 'selected' : '' }}>
                                                {{ ucfirst($ruang->prodi->singkatan) }} - {{ $ruang->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ruang_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label for="matakuliah">Mata Kuliah</label>
                                    <input type="text" name="matakuliah" id="matakuliah"
                                        class="form-control rounded-0 @error('matakuliah') is-invalid @enderror"
                                        value="{{ old('matakuliah') }}">
                                    @error('matakuliah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label for="praktik">Praktik</label>
                                    <input type="text" name="praktik" id="praktik"
                                        class="form-control rounded-0 @error('praktik') is-invalid @enderror"
                                        value="{{ old('praktik') }}">
                                    @error('praktik')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label for="dosen">Dosen Pengampu</label>
                                    <input type="text" name="dosen" id="dosen"
                                        class="form-control rounded-0 @error('dosen') is-invalid @enderror"
                                        value="{{ old('dosen') }}">
                                    @error('dosen')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label for="kelas">Tingkat Kelas</label>
                                    <input type="text" name="kelas" id="kelas"
                                        class="form-control rounded-0 @error('kelas') is-invalid @enderror"
                                        value="{{ old('kelas') }}">
                                    @error('kelas')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card rounded-0 mb-3">
                            <div class="card-header">
                                <h4>Peminjam</h4>
                                <small>(opsional)</small>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-2">
                                    <label for="ketua">Ketua</label>
                                    <input type="text" class="form-control rounded-0"
                                        value="{{ auth()->user()->kode }} | {{ auth()->user()->nama }}" readonly>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="anggota">Anggota</label>
                                    <br>
                                    <button type="button" class="btn btn-warning rounded-0" data-toggle="modal"
                                        data-target="#modal-anggota">
                                        Pilih Anggota
                                    </button>
                                </div>
                            </div>
                            <div class="card-body py-0">
                                @error('anggotas')
                                    <div class="alert alert-danger alert-dismissible show fade rounded-0">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert">
                                                <span>&times;</span>
                                            </button>
                                            {{ $message }}
                                        </div>
                                    </div>
                                @enderror
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-md table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No</th>
                                            <th>Nama</th>
                                        </tr>
                                    </thead>
                                    <tbody id="anggota-tbody">
                                        <tr id="anggota-tbody-empty">
                                            <td class="text-center text-muted" colspan="2">- Anggota belum ditambahkan
                                                -</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
                <div class="mt-4 text-right">
                    <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                        <div id="btn-submit-load" style="display: none;">
                            <i class="fa fa-spinner fa-spin mr-1"></i>
                            Memproses...
                        </div>
                        <span id="btn-submit-text">Buat Peminjaman</span>
                    </button>
                </div>
            </form>
        </div>
    </section>
    <div class="modal fade" id="modal-anggota" data-backdrop="static" role="dialog" aria-labelledby="modal-anggota">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-3 border-bottom">
                    <h5 class="modal-title">Data Mahasiswa</h5>
                </div>
                <div class="modal-header py-3 border-bottom shadow-sm flex-column align-items-stretch">
                    <div class="input-group mb-2">
                        <input type="search" class="form-control rounded-0" id="anggota-keyword" autocomplete="off"
                            placeholder="Cari Nama / NIM">
                        <div class="input-group-append">
                            <button class="btn btn-secondary rounded-0" onclick="anggota_cari()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <select class="custom-select custom-select-sm rounded-0" id="anggota-page" name="anggota_page"
                            style="width: 60px;" onchange="anggota_cari()">
                            <option value="10" {{ Request::get('page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ Request::get('page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ Request::get('page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <div class="custom-checkbox custom-control checkbox-square pt-3">
                            <input type="checkbox" class="custom-control-input" id="anggota-checkbox-seangkatan"
                                onclick="anggota_cari()" checked>
                            <label for="anggota-checkbox-seangkatan" class="custom-control-label">Hanya Seangkatan</label>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="modal-card-anggota">
                        @foreach ($peminjams as $peminjam)
                            <div class="card border rounded-0 mb-2">
                                <label for="anggota-checkbox-{{ $peminjam->id }}"
                                    class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">
                                    <span class="font-weight-normal">
                                        {{ $peminjam->nama }}
                                        <br>
                                        <span class="font-weight-light">{{ $peminjam->kode }}</span>
                                    </span>
                                    <div class="custom-checkbox custom-control checkbox-square">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                            id="anggota-checkbox-{{ $peminjam->id }}"
                                            onclick="anggota_check({{ $peminjam->id }})">
                                        <label for="anggota-checkbox-{{ $peminjam->id }}"
                                            class="custom-control-label"></label>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal-card-anggota-loading" class="text-center p-4" style="display: none">
                        <span class="text-muted">
                            <i class="fas fa-spinner fa-spin fa-sm mr-1"></i>
                            Loading...
                        </span>
                    </div>
                    <div id="modal-card-anggota-empty" class="card border rounded-0 mb-2" style="display: none">
                        <div class="card-body text-center">
                            <span class="text-muted">- Data tidak ditemukan -</span>
                        </div>
                    </div>
                    <div id="modal-card-anggota-limit" class="text-center">
                        <small class="text-muted">Cari dengan <strong>kata kunci</strong> lebih detail</small>
                        <br>
                        <small class="text-muted">
                            Menampilkan maksimal
                            <span id="span-anggota-page">10</span>
                            data
                        </small>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-top shadow-sm">
                    <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary rounded-0" data-dismiss="modal"
                        onclick="anggota_tambah()">Selesai</button>
                </div>
            </div>
        </div>
    </div>
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
        custom_jam();

        function custom_jam() {
            if ($('#jam').val() == 'lainnya') {
                $('#layout_custom_jam').show();
            } else {
                $('#layout_custom_jam').hide();
            }
        }
    </script>
    <script>
        $('#anggota-keyword').on('search', function() {
            anggota_cari();
        });

        var anggota_item = [];

        function anggota_cari() {
            let anggota_keyword = $('#anggota-keyword').val();
            let anggota_checkbox = $('#anggota-checkbox-seangkatan').prop('checked');
            let anggota_page = $('#anggota-page').val();

            console.log(anggota_checkbox);

            $('#modal-card-anggota').empty();
            $('#modal-card-anggota-loading').show();
            $('#modal-card-anggota-empty').hide();
            $('#modal-card-anggota-limit').hide();
            $.ajax({
                url: "{{ url('peminjam/k3/anggota-cari') }}",
                type: "GET",
                data: {
                    "keyword": anggota_keyword,
                    "checkbox": anggota_checkbox,
                    "page": anggota_page,
                },
                dataType: "json",
                success: function(data) {
                    $('#modal-card-anggota-loading').hide();
                    if (data.length) {
                        $('#modal-card-anggota').show();
                        $('#modal-card-anggota-empty').hide();
                        $('#modal-card-anggota-limit').show();
                        $.each(data, function(key, value) {
                            anggota_modal(value, anggota_item.includes(value.id));
                        });
                        $('#span-anggota-page').text(anggota_page);
                    } else {
                        $('#modal-card-anggota').hide();
                        $('#modal-card-anggota-empty').show();
                        $('#modal-card-anggota-limit').hide();
                    }
                },
            });
        }

        function anggota_modal(data, is_selected) {
            if (is_selected) {
                var checked = 'checked';
            } else {
                var checked = '';
            }

            var card_anggota = '<div class="card border rounded-0 mb-2">';
            card_anggota +=
                '<label for="anggota-checkbox-' + data.id +
                '" class="card-body d-flex align-center justify-content-between align-items-center py-2 px-3 mb-0">';
            card_anggota += '<span class="font-weight-normal">';
            card_anggota += data.nama;
            card_anggota += '<br>';
            card_anggota += '<span class="font-weight-light">' + data.kode + '</span>';
            card_anggota += '</span>';
            card_anggota += '<div class="custom-checkbox custom-control checkbox-square">';
            card_anggota +=
                '<input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="anggota-checkbox-' + data
                .id + '" onclick="anggota_check(' + data.id + ')" ' + checked + '>';
            card_anggota += '<label for="anggota-checkbox-' + data.id + '"';
            card_anggota += 'class="custom-control-label"></label>';
            card_anggota += '</div>';
            card_anggota += '</label>';
            card_anggota += '</div>';
            $('#modal-card-anggota').append(card_anggota);
        }

        function anggota_check(id) {
            var check = $('#anggota-checkbox-' + id).prop('checked');
            if (check) {
                anggota_item.push(id);
            } else {
                anggota_item = anggota_item.filter(item => item !== id);
            }
        }

        function anggota_tambah(is_old = false) {
            anggota_loading(true);
            $.ajax({
                url: "{{ url('peminjam/k3/anggota-tambah') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "anggota_item": anggota_item,
                },
                dataType: "json",
                success: function(data) {
                    $('#anggota-tbody').empty();
                    if (data.length) {
                        $.each(data, function(key, value) {
                            anggota_set(key, value, is_old);
                        });
                    } else {
                        var tbody = '<tr>';
                        tbody +=
                            '<td class="text-center text-muted" colspan="2">- Anggota belum ditambahkan -</td>';
                        tbody += '</tr>';
                        $('#anggota-tbody').append(tbody);
                    }
                    anggota_loading(false);
                },
            });
        }

        function anggota_set(key, value, is_old = false) {
            if (is_old) {
                $('#anggota-checkbox-' + value.id).prop('checked', true);
            }
            var no = key + 1;
            var tbody = '<tr id="anggota-tr-' + value.id + '">';
            tbody += '<td class="urutan text-center">' + no + '</td>';
            tbody += '<td class="d-flex justify-content-between align-items-start">';
            tbody += '<span>' + value.nama + '<br>' + value.kode + '</span>';
            tbody +=
                '<button class="btn btn-danger rounded-0" type="button" onclick="anggota_delete(' + value
                .id +
                ')">';
            tbody += '<i class="fas fa-trash"></i>';
            tbody += '</button>';
            tbody += '<input type="hidden" class="form-control rounded-0" name="anggotas[]" value="' + value.id + '">';
            tbody += '</td>';
            tbody += '</tr>';
            $('#anggota-tbody').append(tbody);
        }

        function anggota_delete(id) {
            $('#anggota-tr-' + id).remove();
            anggota_item = anggota_item.filter(item => item !== id);
            $('#anggota-checkbox-' + id).prop('checked', false);
            if (anggota_item.length == 0) {
                var tbody = '<tr>';
                tbody +=
                    '<td class="text-center text-muted" colspan="2">- Anggota belum ditambahkan -</td>';
                tbody += '</tr>';
                $('#anggota-tbody').append(tbody);
            } else {
                var urutan = $('.urutan');
                for (let i = 0; i < urutan.length; i++) {
                    urutan[i].innerText = i + 1;
                }
            }
        }

        var anggotas = @json(old('anggotas'));
        if (anggotas !== null) {
            if (anggotas.length > 0) {
                $('#anggota-tbody').empty();
                $.each(anggotas, function(key, value) {
                    anggota_item.push(parseInt(value));
                });
                anggota_tambah(true);
            }
        }

        function anggota_loading(is_aktif) {
            if (is_aktif) {
                $('#anggota-tbody').empty();
                var loading = '<tr>';
                loading += '<td class="text-center" colspan="2">';
                loading += '<span class="text-muted">';
                loading += '<i class="fas fa-spinner fa-spin fa-sm mr-1"></i>';
                loading += 'Loading...';
                loading += '</span>';
                loading += '</td>';
                loading += '</tr>';
                $('#anggota-tbody').append(loading);
                $('#btn-submit').prop('disabled', true);
            } else {
                $('#btn-submit').prop('disabled', false);
            }
        }
    </script>
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
                url: "{{ url('peminjam/k3/barang-cari') }}",
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
            card_items += '<div class="custom-checkbox custom-control checkbox-square">';
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
                        url: "{{ url('peminjam/k3/barang-tambah') }}" + '/' + id,
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
            } else {
                $('#barang-kosong').show();
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
                '<button class="btn btn-danger rounded-0" type="button" onclick="barang_hapus(' + value
                .id + ')">';
            col += '<i class="fas fa-trash"></i>';
            col += '</button>';
            col += '</div>';
            col += '</div>';
            col += '<div class="card-body border-top">';
            col += '<div class="input-group">';
            col += '<div class="input-group-prepend">';
            col +=
                '<button class="btn btn-secondary rounded-0" type="button" onclick="barang_minus_item(' + value
                .id +
                ')">';
            col += '<i class="fas fa-minus"></i>';
            col += '</button>';
            col += '</div>';
            col += '<input type="text" class="form-control rounded-0 text-center" id="barang-jumlah-' + value.id +
                '" name="barangs[' + key + '][jumlah]" value="' + jumlah + '" readonly>';
            col += '<div class="input-group-append">';
            col +=
                '<button class="btn btn-secondary rounded-0" type="button" onclick="barang_plus_item(' +
                value
                .id +
                ')">';
            col += '<i class="fas fa-plus"></i>';
            col += '</button>';
            col += '</div>';
            col += '</div>';
            col += '</div>';
            col += '<input type="hidden" class="form-control rounded-0 text-center" name="barangs[' + key +
                '][id]" value="' + value.id + '" readonly>';
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

        var old_barangs = @json(session('old_barangs'));
        if (old_barangs !== null) {
            $('#barang-list').empty();
            if (old_barangs.length > 0) {
                $('#barang-kosong').hide();
                $.each(old_barangs, function(key, value) {
                    barang_item.push(parseInt(value.id));
                    $('#barang-checkbox-' + value.id).prop('checked', true);
                    barang_set(key, value, true);
                });
            } else {
                $('#barang-kosong').show();
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
