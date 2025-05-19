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
                        <div class="card rounded-0 mb-3">
                            <div class="card-body">
                                <h6>{{ $kuesioner->judul }}</h6>
                            </div>
                            <div class="card-body border-top text-right">
                                @if (count($is_selesai) > 0)
                                    <p class="p-2 border rounded-0 text-center text-muted">Anda sudah mengisi kuisioner</p>
                                @else
                                    <a href="{{ url('peminjam/kuesioner/' . $kuesioner->id) }}"
                                        class="btn btn-primary rounded-0">Isi Kuisioner</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
