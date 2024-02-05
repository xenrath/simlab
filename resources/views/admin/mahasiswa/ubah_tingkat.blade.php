@extends('layouts.app')

@section('title', 'Ubah Tingkat')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('admin/mahasiswa') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1>Mahasiswa</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Data</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('admin/mahasiswa/ubah_tingkat') }}" method="GET" id="form-prodi">
                                <div class="form-group mb-3">
                                    <label for="filter_subprodi_id">Pilih Prodi</label>
                                    <select class="form-control selectric" id="filter_subprodi_id"
                                        name="filter_subprodi_id">
                                        <option value="">Semua Prodi</option>
                                        @foreach ($subprodis as $subprodi)
                                            <option value="{{ $subprodi->id }}"
                                                {{ request()->get('filter_subprodi_id') == $subprodi->id ? 'selected' : '' }}>
                                                {{ $subprodi->jenjang }}
                                                {{ $subprodi->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="filter_tingkat">Pilih Tingkat</label>
                                    <select class="form-control selectric" id="filter_tingkat" name="filter_tingkat">
                                        <option value="">Semua Tingkat</option>
                                        <option value="1"
                                            {{ request()->get('filter_tingkat') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2"
                                            {{ request()->get('filter_tingkat') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3"
                                            {{ request()->get('filter_tingkat') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4"
                                            {{ request()->get('filter_tingkat') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="0"
                                            {{ request()->get('filter_tingkat') == '0' ? 'selected' : '' }}>Non Aktif
                                        </option>
                                    </select>
                                </div>
                            </form>
                            <button type="button" class="btn btn-outline-info btn-block"
                                onclick="document.getElementById('form-prodi').submit()">
                                <i class="fas fa-search"></i>
                                <span>Cari</span>
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Ubah Tingkat</h4>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-info btn-block" onclick="pilih_semua()">
                                <i class="fas fa-check-square"></i>
                                <span>Pilih Semua</span>
                            </button>
                            <hr>
                            <div class="form-group mb-3">
                                <label>Ubah Tingkat</label>
                                <select class="form-control selectric" onchange="set_tingkat(this.value)">
                                    <option value="">Pilih Tingkat</option>
                                    <option value="1" {{ old('tingkat') == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('tingkat') == '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('tingkat') == '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ old('tingkat') == '4' ? 'selected' : '' }}>4</option>
                                    <option value="0" {{ old('tingkat') == '0' ? 'selected' : '' }}>Non Aktif</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary btn-block"
                                onclick="document.getElementById('form-ubah-tingkat').submit()">
                                <span>Submit</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <form action="{{ url('admin/mahasiswa/ubah_tingkat_proses') }}" method="POST" id="form-ubah-tingkat">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Mahasiswa</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-md">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 20px">#</th>
                                                <th style="width: 20px"">NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Tingkat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <input type="hidden" id="tingkat" name="tingkat"
                                                value="{{ old('tingkat') }}">
                                            @forelse ($users as $user)
                                                @php
                                                    $is_checked = '';
                                                    if (old('user_id')) {
                                                        if (in_array($user->id, old('user_id'))) {
                                                            $is_checked = 'checked';
                                                        }
                                                    }
                                                @endphp
                                                <tr style="cursor: pointer">
                                                    <td class="pl-3 pr-2">
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox" data-checkboxes="mygroup"
                                                                class="custom-control-input" id="{{ $user->id }}"
                                                                name="user_id[]" value="{{ $user->id }}"
                                                                {{ $is_checked }}>
                                                            <label for="{{ $user->id }}"
                                                                class="custom-control-label">&nbsp;</label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->kode }}</td>
                                                    <td>{{ $user->nama }}</td>
                                                    <td>
                                                        {{ $user->subprodi->jenjang }}
                                                        {{ $user->subprodi->nama }}
                                                    </td>
                                                    <td>
                                                        {{ $user->tingkat ?? '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">- Data tidak ditemukan -</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        function set_tingkat(value) {
            $('#tingkat').val(value);
        }

        function pilih_semua() {
            $('input:checkbox').prop('checked', true);
        }
    </script>
@endsection
