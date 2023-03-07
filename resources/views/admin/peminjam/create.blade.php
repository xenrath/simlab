@extends('layouts.app')

@section('title', 'Tambah Peminjam')

@section('content')
  <section class="section">
    <div class="section-header">
      <div class="section-header-back">
        <a href="{{ url('admin/peminjam') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <h1>Peminjam</h1>
    </div>
    @if (session('status'))
      <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
        <div class="alert-icon">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-body">
          <div class="alert-title">Error!</div>
          <button class="close" data-dismiss="alert">
            <span>&times;</span>
          </button>
          <p>
            @foreach (session('status') as $error)
              <span class="bullet"></span>&nbsp;{{ $error }}
              <br>
            @endforeach
          </p>
        </div>
      </div>
    @endif
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Tambah Peminjam</h4>
        </div>
        <form action="{{ url('admin/peminjam') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="kategori">Kategori *</label>
              <select class="form-control selectric" name="kategori" id="kategori" onchange="get_kategori()">
                <option value="1" {{ old('kategori') == '1' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="2" {{ old('kategori') == '2' ? 'selected' : '' }}>Tamu</option>
              </select>
            </div>
            <div id="layout_1">
              <div class="form-group">
                <label for="username_1">NIM *</label>
                <input type="text" name="username_1" id="username_1" class="form-control"
                  value="{{ old('username_1') }}">
              </div>
              <div class="form-group">
                <label for="nama_1">Nama Lengkap *</label>
                <input type="text" name="nama_1" id="nama_1" class="form-control" value="{{ old('nama_1') }}">
              </div>
              <div class="form-group">
                <label for="subprodi_id_1">Prodi *</label>
                <select class="form-control selectric" name="subprodi_id_1" id="subprodi_id_1">
                  <option value="" {{ old('subprodi_id_1') == '' ? 'selected' : '' }}>- Pilih Prodi -</option>
                  @foreach ($subprodis as $subprodi)
                    <option value="{{ $subprodi->id }}" {{ old('subprodi_id_1') == $subprodi->id ? 'selected' : '' }}>
                      {{ $subprodi->jenjang }} {{ $subprodi->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="semester_1">Semester *</label>
                <select class="form-control selectric" name="semester_1" id="semester_1">
                  <option value="">- Pilih Semester -</option>
                  <option value="1" {{ old('semester_1') == '1' ? 'selected' : '' }}>1</option>
                  <option value="2" {{ old('semester_1') == '2' ? 'selected' : '' }}>2</option>
                  <option value="3" {{ old('semester_1') == '3' ? 'selected' : '' }}>3</option>
                  <option value="4" {{ old('semester_1') == '4' ? 'selected' : '' }}>4</option>
                  <option value="5" {{ old('semester_1') == '5' ? 'selected' : '' }}>5</option>
                  <option value="6" {{ old('semester_1') == '6' ? 'selected' : '' }}>6</option>
                  <option value="7" {{ old('semester_1') == '7' ? 'selected' : '' }}>7</option>
                  <option value="8" {{ old('semester_1') == '8' ? 'selected' : '' }}>8</option>
                </select>
              </div>
              <div class="form-group">
                <label for="telp_1">No. Telepon (opsional)</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+62</div>
                  </div>
                  <input type="text" class="form-control" name="telp_1" id="telp_1" value="{{ old('telp_1') }}">
                </div>
              </div>
              <div class="form-group">
                <label for="alamat_1">Alamat (opsional)</label>
                <textarea name="alamat_1" id="alamat_1" cols="30" rows="10" class="form-control">{{ old('alamat_1') }}</textarea>
              </div>
              <div class="form-group">
                <label for="foto_1">Foto (opsional)</label>
                <input type="file" name="foto_1" id="foto_1" class="form-control" value="{{ old('foto_1') }}"
                  accept="image/*">
              </div>
            </div>
            <div id="layout_2">
              <div class="form-group">
                <label for="nama_2">Nama Instansi *</label>
                <input type="text" name="nama_2" id="nama_2" class="form-control" value="{{ old('nama_2') }}">
              </div>
              <div class="form-group">
                <label for="telp_2">Nomor yang dapat dihubungi *</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+62</div>
                  </div>
                  <input type="text" class="form-control" name="telp_2" id="telp_2"
                    value="{{ old('telp_2') }}">
                </div>
              </div>
              <div class="form-group">
                <label for="alamat_2">Alamat Instansi *</label>
                <textarea name="alamat_2" id="alamat_2" cols="30" rows="10" class="form-control">{{ old('alamat_2') }}</textarea>
              </div>
            </div>
          </div>
          <div class="card-footer float-right">
            <button type="reset" class="btn btn-secondary mr-1">
              Reset
            </button>
            <button type="submit" class="btn btn-primary">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
  <script>
    var kategori = document.getElementById('kategori');
    var layout_1 = document.getElementById('layout_1');
    var layout_2 = document.getElementById('layout_2');

    if (kategori.value == '1') {
      layout_1.style.display = "inline";
      layout_2.style.display = "none";
    } else if (kategori.value == '2') {
      layout_1.style.display = "none";
      layout_2.style.display = "inline";
    }

    function get_kategori() {
      if (kategori.value == '1') {
        layout_1.style.display = "inline";
        layout_2.style.display = "none";
      } else if (kategori.value == '2') {
        layout_1.style.display = "none";
        layout_2.style.display = "inline";
      }
      console.log(kategori.value);
    }
  </script>
@endsection
