@extends('layouts.app')

@section('title', 'Praktik Pinjam Ruang')

@section('style')
    <!-- Custom -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/labterpadu/buat') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Praktik Pinjam Ruang</h1>
        </div>
        <div class="section-body">
            <form action="{{ url('peminjam/labterpadu/buat/store-praktik-ruang') }}" method="POST" autocomplete="off"
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
                                <div class="alert alert-info alert-dismissible show fade rounded-0" id="anggota-alert"
                                    style="display: none;">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        Lakukan <strong>uncheck</strong> untuk menghapus anggota peminjaman
                                    </div>
                                </div>
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
                <div class="mt-4 mb-2 text-right">
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
                <div class="modal-header py-3 border-bottom shadow-sm">
                    <div class="input-group">
                        <input type="search" class="form-control rounded-0" id="keyword-anggota" autocomplete="off"
                            placeholder="Cari NIM / Nama">
                        <div class="input-group-append">
                            <button class="btn btn-secondary rounded-0" onclick="anggota_search()">
                                <i class="fas fa-search"></i>
                            </button>
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
                                    <div class="custom-checkbox custom-control">
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
                        <small class="text-muted">Menampilkan maksimal 10 data</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-between border-top shadow-sm">
                    <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary rounded-0" data-dismiss="modal"
                        onclick="anggota_get()">Selesai</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        customJam();
        // 
        function customJam() {
            if ($('#jam').val() == 'lainnya') {
                $('#layout_custom_jam').show();
            } else {
                $('#layout_custom_jam').hide();
            }
        }
    </script>
    <script type="text/javascript">
        $('#keyword-anggota').on('search', function() {
            anggota_search();
        });

        var anggota_item = [];

        function anggota_search() {
            $('#modal-card-anggota').empty();
            $('#modal-card-anggota-loading').show();
            $('#modal-card-anggota-empty').hide();
            $('#modal-card-anggota-limit').hide();
            $.ajax({
                url: "{{ url('peminjam/search_anggotas') }}",
                type: "GET",
                data: {
                    "keyword": $('#keyword-anggota').val()
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
            card_anggota += '<div class="custom-checkbox custom-control">';
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

        function anggota_get(is_old = false) {
            anggota_loading(true);
            $.ajax({
                url: "{{ url('anggota-get') }}",
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
                        $('#anggota-alert').show();
                    } else {
                        var tbody = '<tr>';
                        tbody +=
                            '<td class="text-center text-muted" colspan="2">- Anggota belum ditambahkan -</td>';
                        tbody += '</tr>';
                        $('#anggota-tbody').append(tbody);
                        $('#anggota-alert').hide();
                    }
                    anggota_loading(false);
                },
            });
        }
        // 
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
                '<button class="btn btn-danger rounded-0" type="button" id="minus-' + value.id +
                '" onclick="anggota_delete(' + value
                .id +
                ')">';
            tbody += '<i class="fas fa-trash"></i>';
            tbody += '</button>';
            tbody += '<input type="hidden" class="form-control rounded-0" name="anggotas[]" value="' + value.id + '">';
            tbody += '</td>';
            tbody += '</tr>';
            $('#anggota-tbody').append(tbody);
        }
        // 
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
                $('#anggota-alert').hide();
            } else {
                var urutan = $('.urutan');
                for (let i = 0; i < urutan.length; i++) {
                    urutan[i].innerText = i + 1;
                }
            }
        }
        // 
        var anggotas = @json(old('anggotas'));
        if (anggotas !== null) {
            if (anggotas.length > 0) {
                $('#anggota-tbody').empty();
                $.each(anggotas, function(key, value) {
                    anggota_item.push(parseInt(value));
                });
                anggota_get(true);
            }
        }
        // 
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
    <script type="text/javascript">
        function form_submit() {
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit-text').hide();
            $('#btn-submit-load').show();
            $('#form-submit').submit();
        }
    </script>
@endsection
