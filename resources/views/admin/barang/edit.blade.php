@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/barang') }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Barang</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Edit Barang</h4>
                </div>
                <form action="{{ url('admin/barang/' . $barang->id) }}" method="POST" autocomplete="off" id="form-submit">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label for="kode">Kode *</label>
                            <input type="text" name="kode" id="kode"
                                class="form-control rounded-0 @error('kode') is-invalid @enderror"
                                value="{{ old('kode', $barang->kode) }}">
                            @error('kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="nama">Nama Barang *</label>
                            <input type="text" name="nama" id="nama"
                                class="form-control rounded-0 @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $barang->nama) }}">
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="ruang_id">Ruang Lab *</label>
                            <div class="input-group">
                                <select
                                    class="custom-select custom-select-sm rounded-0 @error('ruang_id') is-invalid @enderror"
                                    name="ruang_id" id="ruang_id">
                                    <option value="">- Pilih -</option>
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary rounded-0" data-toggle="modal"
                                        data-target="#modal-ruang">Pilih</button>
                                </div>
                            </div>
                            @error('ruang_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="normal">Jumlah Baik *</label>
                            <input type="number" name="normal" id="normal" class="form-control rounded-0"
                                value="{{ old('normal', $barang->normal) }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="rusak">
                                Jumlah Rusak
                                <small class="text-muted">(optional)</small>
                            </label>
                            <input type="number" name="rusak" id="rusak" class="form-control rounded-0"
                                value="{{ old('rusak', $barang->rusak) }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="keterangan">
                                Keterangan
                                <small class="text-muted">(optional)</small>
                            </label>
                            <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control rounded-0"
                                style="height: 80px">{{ old('keterangan', $barang->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="button" class="btn btn-primary rounded-0" id="btn-submit" onclick="form_submit()">
                            <div id="btn-submit-load" style="display: none;">
                                <i class="fa fa-spinner fa-spin mr-1"></i>
                                Memproses...
                            </div>
                            <span id="btn-submit-text">Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-ruang" data-backdrop="static" role="dialog" aria-labelledby="modal-ruang">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header pb-3 border-bottom">
                    <h5 class="modal-title">Data Ruang Lab</h5>
                </div>
                <div class="modal-header py-3 border-bottom shadow-sm flex-column align-items-stretch">
                    <div class="input-group mb-2">
                        <input type="search" class="form-control rounded-0" id="ruang-keyword" autocomplete="off"
                            placeholder="Cari Nama Ruang">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0" onclick="ruang_search()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <select class="custom-select custom-select-sm rounded-0" id="ruang-page" name="ruang_page"
                        style="width: 60px;" onchange="ruang_search()">
                        <option value="10" {{ Request::get('page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ Request::get('page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ Request::get('page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ Request::get('page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="modal-body">
                    <div id="modal-card-ruang">
                        @foreach ($ruangs as $ruang)
                            <div class="card border rounded-0 mb-2">
                                <div class="card-body d-flex justify-content-between align-items-center py-2 px-3 mb-0">
                                    <span class="font-weight-normal">
                                        {{ $ruang->nama }}<br>
                                        <small class="font-weight-light">
                                            @if ($ruang->prodi)
                                                ({{ strtoupper($ruang->prodi->nama) }})
                                            @endif
                                        </small>
                                    </span>
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-0"
                                        onclick="ruang_set({{ $ruang->id }})" data-dismiss="modal">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="modal-card-ruang-loading" class="text-center p-4" style="display: none">
                        <span class="text-muted">
                            <i class="fas fa-spinner fa-spin fa-sm mr-1"></i>
                            Loading...
                        </span>
                    </div>
                    <div id="modal-card-ruang-empty" class="card border rounded-0 mb-2" style="display: none">
                        <div class="card-body text-center">
                            <span class="text-muted">- Data tidak ditemukan -</span>
                        </div>
                    </div>
                    <div id="modal-card-ruang-limit" class="text-center">
                        <small class="text-muted">Cari dengan <strong>kata kunci</strong> lebih detail</small>
                        <br>
                        <small class="text-muted">
                            Menampilkan maksimal
                            <span id="span-ruang-page">10</span>
                            data
                        </small>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#ruang-keyword').on('search', function() {
            ruang_search();
        });
        // 
        function ruang_search() {
            let keyword = $('#ruang-keyword').val();
            let page = $('#ruang-page').val();
            // 
            $('#modal-card-ruang').empty();
            $('#modal-card-ruang-loading').show();
            $('#modal-card-ruang-empty').hide();
            $('#modal-card-ruang-limit').hide();
            // 
            $.ajax({
                url: "{{ url('admin/ruang-search') }}",
                type: "GET",
                data: {
                    "ruang_keyword": keyword,
                    "ruang_page": page
                },
                dataType: "json",
                success: function(data) {
                    $('#modal-card-ruang-loading').hide();
                    if (data.length) {
                        $('#modal-card-ruang').show();
                        $('#modal-card-ruang-empty').hide();
                        $('#modal-card-ruang-limit').show();
                        $.each(data, function(key, value) {
                            ruang_modal(value);
                        });
                        $('#span-ruang-page').text(page);
                    } else {
                        $('#modal-card-ruang').hide();
                        $('#modal-card-ruang-empty').show();
                        $('#modal-card-ruang-limit').hide();
                    }
                },
            });
        }
        // 
        function ruang_modal(data) {
            var prodi = '';
            if (data.prodi) {
                var prodi = '(' + (data.prodi.nama || "").toUpperCase() + ')';
            }
            var card_ruang = '<div class="card border rounded-0 mb-2">';
            card_ruang += '<div class="card-body d-flex justify-content-between align-items-center py-2 px-3 mb-0">';
            card_ruang += '<span class="font-weight-normal">' + data.nama + '<br>';
            card_ruang += '<small class="font-weight-light">' + prodi + '</small>';
            card_ruang += '</span>';
            card_ruang += '<button type="button" class="btn btn-outline-primary btn-sm rounded-0" onclick="ruang_set(' +
                data
                .id + ')" data-dismiss="modal">';
            card_ruang += '<i class="fas fa-check"></i>';
            card_ruang += '</button>';
            card_ruang += '</div>';
            card_ruang += '</div>';
            $('#modal-card-ruang').append(card_ruang);
        }
        // 
        function ruang_set(id) {
            $('#ruang_id').empty();
            var option = '<option>Loading...</option>';
            $('#ruang_id').append(option);
            $.ajax({
                url: "{{ url('admin/ruang-set') }}" + "/" + id,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#ruang_id').empty();
                    if (data) {
                        var option = '<option value="' + data.id + '">' + data.nama + '</option>';
                        $('#ruang_id').append(option);
                    } else {
                        var option = '<option value="">- Pilih -</option>';
                        console.log('Tamu tidak ditemukan!');
                    }
                },
            });
        }
        // 
        var ruang_id = "{{ old('ruang_id', $barang->ruang_id) }}";
        if (ruang_id !== '') {
            ruang_set(ruang_id);
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
