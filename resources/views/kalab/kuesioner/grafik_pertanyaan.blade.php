@extends('layouts.app')

@section('title', 'Grafik Kuesioner')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('kalab/kuesioner/' . $kuesioner->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Grafik Kuesioner</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Grafik</h4>
                </div>
                <div class="card-body">
                    @foreach ($pertanyaan_kuesioners as $key => $pertanyaan_kuesioner)
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>
                                        <strong>Pertanyaan : {{ $key + 1 }}</strong>
                                        <br>
                                        {{ $pertanyaan_kuesioner->pertanyaan }}
                                    </span>
                                    <div class="d-inline">
                                        <a href="" id="download-{{ $pertanyaan_kuesioner->id }}"
                                            class="btn btn-outline-primary btn-sm"
                                            download="{{ $kuesioner->singkatan }}-{{ $key + 1 }}.png">
                                            <i class="fas fa-download"></i>
                                            <span class="d-none d-lg-inline">Unduh</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <canvas id="grafik-{{ $pertanyaan_kuesioner->id }}" style="padding: 10px"
                                            class="border"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
@section('chart')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        var pertanyaan_kuesioners = @json($pertanyaan_kuesioners);
        for (let i = 0; i < pertanyaan_kuesioners.length; i++) {
            var ctx = document.getElementById('grafik-' + pertanyaan_kuesioners[i]['id']);
            var data_label = @json($data)[pertanyaan_kuesioners[i]['id']];
            var data = data_label['data'];
            var label = data_label['label'];
            var no = i + 1;
            new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: label,
                    datasets: [{
                        label: 'Jumlah responden',
                        data: data,
                        backgroundColor: [
                            '#952B60',
                            '#F1B620',
                            '#626520',
                            '#3D91E0',
                        ],
                        hoverOffset: 6
                    }]
                },
                options: {
                    bezierCurve: false,
                    animation: {
                        onComplete: function() {
                            document.getElementById('download-' + pertanyaan_kuesioners[i]['id']).href =
                                document.getElementById('grafik-' + pertanyaan_kuesioners[i]['id']).toDataURL(
                                    'image/png');
                        }
                    },
                    tooltips: {
                        enabled: false,
                    },
                    plugins: {
                        colors: {
                            enabled: false
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                const datapoints = ctx.chart.data.datasets[0].data
                                const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                                const percentage = value / total * 100
                                return Math.floor(percentage.toFixed(2)) + "%";
                            },
                            align: 'top',
                            color: 'white',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            display: function(context) {
                                return context.dataset.data[context.dataIndex] !== 0; // or >= 1 or ...
                            }
                        },
                    }
                },
                plugins: [ChartDataLabels]
            });
        }
    </script>
@endsection
