@extends('layouts.app')

@section('title', 'Grafik Barang')

@section('style')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Grafik Barang</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Data Grafik Barang</h4>
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-md-6">
                            <div class="alert alert-light rounded-0 mb-2">
                                <table>
                                    <tr>
                                        <td style="width: 80px;">
                                            <strong>Prodi</strong>
                                        </td>
                                        <td style="width: 20px;" class="text-center">
                                            <strong>:</strong>
                                        </td>
                                        <td>
                                            {{ Request::get('prodi_id') ? (Request::get('prodi_id') != '5' ? 'Prodi' : '') : '' }}
                                            {{ Request::get('prodi_id') ? ucfirst($prodis->firstWhere('id', Request::get('prodi_id'))->nama) : 'Semua' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 80px;">
                                            <strong>Kategori</strong>
                                        </td>
                                        <td style="width: 20px;" class="text-center">
                                            <strong>:</strong>
                                        </td>
                                        <td>
                                            {{ Request::get('peminjam') ? (Request::get('peminjam') == 'mahasiswa' ? 'Mahasiswa' : 'Peminjaman Luar') : 'Semua' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 80px;">
                                            <strong>Tahun</strong>
                                        </td>
                                        <td style="width: 20px;" class="text-center">
                                            <strong>:</strong>
                                        </td>
                                        <td>
                                            {{ Request::get('tahun') ?? 'Semua' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="button"
                                class="btn {{ session('filter') ? 'btn-outline-danger' : 'btn-primary' }} rounded-0 float-right mb-2"
                                data-toggle="modal" data-target="#modal-filter">
                                <i class="fas fa-chart-bar"></i>
                                Filter Grafik
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <div class="row mb-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-dark rounded-0" onclick="form_print()">
                                <i class="fas fa-print"></i>
                                Print
                            </button>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-end">
                                <select class="form-control rounded-0" id="page_main" name="page_main" style="width: 80px;"
                                    onchange="form_filter(true)">
                                    <option value="10" {{ Request::get('page') == 10 ? 'selected' : '' }}>
                                        10
                                    </option>
                                    <option value="25" {{ Request::get('page') == 25 ? 'selected' : '' }}>
                                        25
                                    </option>
                                    <option value="50" {{ Request::get('page') == 50 ? 'selected' : '' }}>
                                        50
                                    </option>
                                    <option value="100" {{ Request::get('page') == 100 ? 'selected' : '' }}>
                                        100
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if (count($labels) > 0)
                        <canvas id="grafik-barang" height="{{ count($barangs) * 20 }}"></canvas>
                    @else
                        <div class="p-4 border">
                            <h6 class="text-center">Gagal menampilkan Grafik!</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-filter">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Grafik</h5>
                </div>
                <form action="{{ url('kalab/grafik-barang') }}" method="GET" autocomplete="off" id="form-filter">
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="prodi_id">Pilih Prodi</label>
                            <select class="form-control rounded-0" id="prodi_id" name="prodi_id">
                                <option value=""> - Semua -</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ Request::get('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        @if ($prodi->id != '5')
                                            Prodi
                                        @endif
                                        {{ ucfirst($prodi->nama) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="peminjam">Kategori Peminjam</label>
                            <select class="form-control rounded-0" id="peminjam" name="peminjam">
                                <option value="">- Semua -</option>
                                <option value="mahasiswa" {{ Request::get('peminjam') == 'mahasiswa' ? 'selected' : '' }}>
                                    Mahasiswa
                                </option>
                                <option value="tamu" {{ Request::get('peminjam') == 'tamu' ? 'selected' : '' }}>
                                    Peminjam Luar</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="tahun">Tahun Peminjaman</label>
                            <select class="form-control rounded-0" id="tahun" name="tahun">
                                <option value=""> - Semua -</option>
                                @foreach ($tahuns as $tahun)
                                    <option value="{{ $tahun->nama }}"
                                        {{ Request::get('tahun') == $tahun->nama ? 'selected' : '' }}>
                                        {{ $tahun->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="page" name="page" class="form-control rounded-0" value="">
                    </div>
                </form>
                <div class="modal-footer bg-whitesmoke justify-content-between">
                    <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary rounded-0" id="btn-filter" onclick="form_filter()">
                        <div id="btn-filter-load" style="display: none;">
                            <i class="fa fa-spinner fa-spin mr-1"></i>
                            Memproses...
                        </div>
                        <span id="btn-filter-text">Filter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const ctx = $('#grafik-barang')[0];
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {{ Js::from($labels) }},
                datasets: [{
                    axis: 'y',
                    label: 'Jumlah Pemakaian',
                    barThickness: 40,
                    data: {{ Js::from($data) }},
                    borderWidth: 1,
                    fill: false,
                }]
            },
            options: {
                indexAxis: 'y',
            }
        });
    </script>
    <script>
        function form_filter(page) {
            $('#form-filter').attr('action', "{{ url('kalab/grafik-barang') }}");
            $('#form-filter').removeAttr('target');
            if (page) {
                $('#page').val($('#page_main').val());
            } else {
                $('#page').val('');
            }
            $('#btn-filter').prop('disabled', true);
            $('#btn-filter-text').hide();
            $('#btn-filter-load').show();
            $('#form-filter').submit();
        }
        // 
        function form_print() {
            $('#page').val($('#page_main').val());
            $('#form-filter').attr('action', "{{ url('kalab/grafik-barang/print') }}");
            $('#form-filter').attr('target', '_blank');
            $('#form-filter').submit();
        }
    </script>
@endsection
