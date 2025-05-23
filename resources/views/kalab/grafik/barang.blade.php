@extends('layouts.app')

@section('title', 'Grafik Barang')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Grafik Barang</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Grafik Barang</h4>
                </div>
                <div class="card-body">
                    <form action="" method="get" id="form-submit">
                        <div class="form-group">
                            <div class="input-group">
                                <select class="custom-select custom-select-sm" id="prodi_id" name="prodi_id">
                                    <option value="">Semua Prodi</option>
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
                                <select class="custom-select custom-select-sm" id="peminjam" name="peminjam">
                                    <option value="">Semua Peminjam</option>
                                    <option value="mahasiswa"
                                        {{ Request::get('peminjam') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa
                                    </option>
                                    <option value="tamu" {{ Request::get('peminjam') == 'tamu' ? 'selected' : '' }}>
                                        Peminjam Luar</option>
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" onclick="getSubmit('filter')">Filter
                                        Grafik</button>
                                    <button type="button" class="btn btn-dark" onclick="getSubmit('print')">
                                        <i class="fas fa-print"></i>
                                        Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if (count($labels) > 0)
                        <canvas id="grafik-barang" height="{{ count($barangs) * 40 }}"></canvas>
                    @else
                        <div class="p-5 border">
                            <h5 class="text-center">Gagal menampilkan Grafik!</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
@section('chart')
    <script>
        function getSubmit(params) {
            if (params === 'filter') {
                $('#form-submit').attr('action', "{{ url('kalab/grafik/barang') }}");
            } else {
                $('#form-submit').attr('action', "{{ url('kalab/grafik/barang/print') }}");
            }
            $('#form-submit').submit();
        }

        const ctx = document.getElementById('grafik-barang');
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
@endsection
