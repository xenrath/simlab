@extends('layouts.app')

@section('title', 'Grafik Ruang')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Grafik Ruang</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Data Grafik Ruang</h4>
        </div>
        <div class="card-body">
          <canvas id="grafik-ruang" height="200"></canvas>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('chart')
  <script>
    const ctx = document.getElementById('grafik-ruang');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: {{ Js::from($labels) }},
        datasets: [{
          axis: 'y',
          label: 'Jumlah Pemakaian',
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
