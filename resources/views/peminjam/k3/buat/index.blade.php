@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Buat Peminjaman</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Peminjaman</h4>
                </div>
                <form action="{{ url('peminjam/k3/buat/create') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Kategori Praktik</label>
                            <select name="praktik_id" id="praktik_id" class="custom-select custom-select-sm">
                                <option value="">- Pilih -</option>
                                @foreach ($praktiks as $praktik)
                                    <option value="{{ $praktik->id }}">{{ $praktik->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke text-right">
                        <button type="submit" class="btn btn-primary">Selanjutnya</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        function modalDelete(id) {
            $("#delete-" + id).submit();
        }
    </script>
@endsection
