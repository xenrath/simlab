@extends('layouts.app')

@section('title', 'Kuesioner')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('peminjam/kuesioner') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Kuesioner</h1>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                <div class="alert-body">
                    <div class="alert-title">Error!</div>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <p>
                        @foreach (session('error') as $error)
                            <span class="bullet"></span>&nbsp;{{ $error }}
                            <br>
                        @endforeach
                    </p>
                </div>
            </div>
        @endif
        <div class="section-body">
            <form action="{{ url('peminjam/kuesioner/' . $kuesioner->id) }}" method="POST" autocomplete="off"
                id="form-submit">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $kuesioner->judul }}</h4>
                            </div>
                            @csrf
                            <div class="card-body">
                                @foreach ($pertanyaan_kuesioners as $key => $pertanyaan_kuesioner)
                                    <div class="form-group">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td class="align-top m-0 p-0" style="width: 24px">
                                                        {{ $loop->iteration }}.</td>
                                                    <td class="align-top m-0 p-0">
                                                        <label>{{ $pertanyaan_kuesioner->pertanyaan }}</label>
                                                        <input type="hidden" name="pertanyaan_id[]"
                                                            value="{{ $pertanyaan_kuesioner->id }}">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="jawaban[{{ $pertanyaan_kuesioner->id }}]"
                                                                id="sangat-puas-{{ $pertanyaan_kuesioner->id }}"
                                                                value="4"
                                                                {{ old('jawaban.' . $pertanyaan_kuesioner->id) == '4' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="sangat-puas-{{ $pertanyaan_kuesioner->id }}">
                                                                Sangat Puas
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="jawaban[{{ $pertanyaan_kuesioner->id }}]"
                                                                id="puas-{{ $pertanyaan_kuesioner->id }}" value="3"
                                                                {{ old('jawaban.' . $pertanyaan_kuesioner->id) == '3' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="puas-{{ $pertanyaan_kuesioner->id }}">
                                                                Puas
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="jawaban[{{ $pertanyaan_kuesioner->id }}]"
                                                                id="kurang-puas-{{ $pertanyaan_kuesioner->id }}"
                                                                value="2"
                                                                {{ old('jawaban.' . $pertanyaan_kuesioner->id) == '2' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="kurang-puas-{{ $pertanyaan_kuesioner->id }}">
                                                                Kurang Puas
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="jawaban[{{ $pertanyaan_kuesioner->id }}]"
                                                                id="tidak-puas-{{ $pertanyaan_kuesioner->id }}"
                                                                value="1"
                                                                {{ old('jawaban.' . $pertanyaan_kuesioner->id) == '1' ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="tidak-puas-{{ $pertanyaan_kuesioner->id }}">
                                                                Tidak Puas
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                            <div class="card-footer bg-whitesmoke text-right">
                                <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
