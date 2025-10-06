@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Buat Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card rounded-0">
                <div class="card-header">
                    <h4>Peminjaman</h4>
                </div>
                <form action="{{ url('peminjam/labterpadu/buat/create') }}" method="get">
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label>Kategori Praktik</label>
                            <select name="praktik_id" id="praktik_id" class="form-control rounded-0">
                                <option value="">- Pilih -</option>
                                @foreach ($praktiks as $praktik)
                                    <option value="{{ $praktik->id }}">{{ $praktik->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="submit" class="btn btn-primary rounded-0">Selanjutnya</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
