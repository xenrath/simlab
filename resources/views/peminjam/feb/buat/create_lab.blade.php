@extends('layouts.app')

@section('title', 'Buat Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/feb/buat') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Buat Peminjaman</h1>
        </div>
        <div class="section-body">
            <form action="{{ url('peminjam/feb/buat') }}" method="POST" autocomplete="off">
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
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Kategori Peminjaman</label>
                            <input type="text" class="form-control" value="Ruang dan Komputer" readonly>
                            <input type="hidden" name="praktik_id" class="form-control" value="1">
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="{{ old('tanggal') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="jam">Jam Pinjam</label>
                            <select class="custom-select custom-select-sm" name="jam" id="jam"
                                onchange="custom_jam()">
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
                            <label for="ruang_id">Ruang Lab</label>
                            <select class="custom-select custom-select-sm" id="ruang_id" name="ruang_id">
                                <option value="">- Pilih -</option>
                                @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}"
                                        {{ old('ruang_id') == $ruang->id ? 'selected' : '' }}>
                                        {{ $ruang->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="jumlah">Jumlah komputer yang dipinjam</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control"
                                value="{{ old('jumlah') }}">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Buat Peminjaman</button>
                </div>
            </form>
        </div>
    </section>
    <script type="text/javascript">
        custom_jam();

        function custom_jam() {
            if ($('#jam').val() == 'lainnya') {
                $('#layout_custom_jam').show();
            } else {
                $('#layout_custom_jam').hide();
            }
        }
    </script>
@endsection
