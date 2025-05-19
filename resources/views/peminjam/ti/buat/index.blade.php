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
                <form action="{{ url('peminjam/ti/buat/create') }}" method="get">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Kategori Peminjaman</label>
                            <select name="praktik_id" id="praktik_id" class="custom-select custom-select-sm">
                                <option value="">- Pilih -</option>
                                <option value="1">Ruang Lab dan Komputer</option>
                                <option value="3">Komputer</option>
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
