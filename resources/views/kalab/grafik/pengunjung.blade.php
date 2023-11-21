@extends('layouts.app')

@section('title', 'Grafik Pengunjung')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Grafik Pengunjung</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Grafik Pengunjung</h4>
                </div>
                <div class="card-body">
                    <canvas id="grafik-pengunjung"></canvas>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('chart')
    <script>
        const ctx = document.getElementById('grafik-pengunjung');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {{ Js::from($labels) }},
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    barThickness: 60,
                    data: {{ Js::from($data) }},
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
