@extends('layouts.app')

@section('title', 'Data Saran')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Saran</h1>
        </div>
        <div class="section-body">
            <div class="row">
                @foreach ($sarans as $saran)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ ucfirst($saran->kategori) }}</h4>
                                <div class="card-header-action">
                                    <a data-collapse="#saran{{ $saran->id }}" class="btn btn-icon btn-info" href="#">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse" id="saran{{ $saran->id }}">
                                <div class="card-body">
                                    <div class="mb-2 text-muted">
                                        {!! $saran->saran !!}
                                    </div>
                                    @if ($saran->gambar)
                                        <div class="chocolat-parent">
                                            <a href="{{ asset('storage/uploads/' . $saran->gambar) }}"
                                                class="chocolat-image" title="Just an example">
                                                <div data-crop-image="285">
                                                    <img alt="image"
                                                        src="{{ asset('storage/uploads/' . $saran->gambar) }}"
                                                        class="img-fluid rounded">
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        </div>
    </section>
    <script>
        function modalDelete(id) {
            $("#del-" + id).submit();
        }
    </script>
@endsection
