@extends('layouts.app')

@section('title', 'Tata Cara')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Kuesioner</h1>
        </div>
        <div class="section-body">
            <div class="row">
                @foreach ($kuesioners as $kuesioner)
                    @php
                        $is_selesai = \App\Models\JawabanKuesioner::where('peminjam_id', auth()->user()->id)
                            ->whereHas('pertanyaankuesioner', function ($query) use ($kuesioner) {
                                $query->where('kuesioner_id', $kuesioner->id);
                            })
                            ->get();
                    @endphp
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>{{ $kuesioner->judul }}</h6>
                            </div>
                            <div class="card-footer text-right">
                                @if (count($is_selesai) > 0)
                                    <p class="p-2 border rounded text-center">Anda sudah mengisi kuisioner</p>
                                @else
                                    <a href="{{ url('peminjam/kuesioner/' . $kuesioner->id) }}" class="btn btn-primary">Isi
                                        Kuisioner</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
