@extends('layouts.app')

@section('title', 'Grafik Kuesioner')

@section('style')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('kalab/kuesioner/' . $kuesioner->id . '/' . $tahun) }}" class="btn btn-secondary rounded-0">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Grafik Kuesioner</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0 mb-3">
                <div class="card-header">
                    <h4>Detail Grafik</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>
                                <strong>Judul Kuesioner</strong>
                                <br>
                                {{ $kuesioner->judul }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Tahun</strong>
                                <br>
                                {{ $tahun }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($subprodis as $key => $subprodi)
                    <div class="col-md-6">
                        <div class="card rounded-0 mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <strong>Prodi {{ $subprodi->jenjang }} {{ $subprodi->nama }}</strong>
                                    </span>
                                    <a href="" id="download-{{ $subprodi->id }}"
                                        class="btn btn-outline-primary btn-sm rounded-0"
                                        download="{{ $kuesioner->singkatan }}-{{ $key + 1 }}.png">
                                        <i class="fas fa-download"></i>
                                        <span class="d-none d-lg-inline">Unduh</span>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                <canvas id="grafik-{{ $subprodi->id }}" style="padding: 10px" class="border"></canvas>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const subprodis = @json($subprodis);
            const allData = @json($data);
            // 
            $.each(subprodis, function(i, subprodi) {
                const id = subprodi.id;
                const $canvas = $('#grafik-' + id);
                const dataLabel = allData[id];
                // 
                if ($canvas.length === 0 || !dataLabel) return;
                // 
                const ctx = $canvas[0].getContext('2d');
                // 
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: dataLabel.label,
                        datasets: [{
                            label: 'Jumlah responden',
                            data: dataLabel.data,
                            backgroundColor: [
                                '#E74C3C',
                                '#F39C12',
                                '#27AE60',
                                '#3498DB',
                            ],
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            onComplete: function() {
                                const canvas = document.getElementById('grafik-' + subprodis[i][
                                    'id'
                                ]);
                                const ctx = canvas.getContext('2d');
                                const chartImage = canvas.toDataURL('image/png');
                                const tempCanvas = document.createElement('canvas');
                                tempCanvas.width = canvas.width;
                                tempCanvas.height = canvas.height;
                                const tempCtx = tempCanvas.getContext('2d');
                                tempCtx.fillStyle = 'white';
                                tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
                                tempCtx.drawImage(canvas, 0, 0);
                                document.getElementById('download-' + subprodis[i]['id']).href =
                                    tempCanvas
                                    .toDataURL('image/png');
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label;
                                    }
                                }
                            },
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    color: '#333',
                                    padding: 20
                                }
                            },
                            datalabels: {
                                formatter: function(value, context) {
                                    const datapoints = context.chart.data.datasets[0].data;
                                    const total = datapoints.reduce((sum, val) => sum + val, 0);
                                    const percent = total ? (value / total) * 100 : 0;
                                    return Math.floor(percent) + '%';
                                },
                                color: '#000',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                align: 'top',
                                display: function(context) {
                                    return context.dataset.data[context.dataIndex] !== 0;
                                }
                            }
                        },
                    },
                    plugins: [ChartDataLabels]
                });
            });
        });
    </script>
@endsection
