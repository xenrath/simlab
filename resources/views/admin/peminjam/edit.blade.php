@extends('layouts.app')

@section('title', 'Edit Peminjam')

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
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Edit Peminjam</h4>
      </div>
      <form action="{{ url('admin/peminjam/' . $user->id) }}" method="POST" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="username">Username * <small>(masukan username dengan NIM mahasiswa)</small></label>
                <input type="text" name="username" id="username"
                  class="form-control @error('username') is-invalid @enderror"
                  value="{{ old('username', $user->username) }}">
                @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                  value="{{ old('nama', $user->nama) }}">
                @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="subprodi_id">Prodi *</label>
                <select class="form-control selectric @error('subprodi_id') is-invalid @enderror" name="subprodi_id"
                  id="subprodi_id">
                  <option value="" {{ old('subprodi_id')=='' ? 'selected' : '' }}>- Pilih Prodi -</option>
                  @foreach ($subprodis as $subprodi)
                  <option value="{{ $subprodi->id }}" {{ old('subprodi_id', $user->subprodi_id)==$subprodi->id ?
                    'selected' : '' }}>{{ $subprodi->jenjang }} {{ $subprodi->nama }}</option>
                  @endforeach
                </select>
                @error('subprodi_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="semester">Semester *</label>
                <select class="form-control selectric @error('semester') is-invalid @enderror" name="semester"
                  id="semester" required>
                  <option value="" {{ old('semester', $user->semester)=='' ? 'selected' : '' }}>- Pilih Semester -
                  </option>
                  <option value="1" {{ old('semester', $user->semester)=='1' ? 'selected' : '' }}>1</option>
                  <option value="2" {{ old('semester', $user->semester)=='2' ? 'selected' : '' }}>2</option>
                  <option value="3" {{ old('semester', $user->semester)=='3' ? 'selected' : '' }}>3</option>
                  <option value="4" {{ old('semester', $user->semester)=='4' ? 'selected' : '' }}>4</option>
                  <option value="5" {{ old('semester', $user->semester)=='5' ? 'selected' : '' }}>5</option>
                  <option value="6" {{ old('semester', $user->semester)=='6' ? 'selected' : '' }}>6</option>
                  <option value="7" {{ old('semester', $user->semester)=='7' ? 'selected' : '' }}>7</option>
                  <option value="8" {{ old('semester', $user->semester)=='8' ? 'selected' : '' }}>8</option>
                </select>
                @error('semester')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="telp">No. Telepon (opsional)</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">+62</div>
                  </div>
                  <input type="text" class="form-control @error('telp') is-invalid @enderror" name="telp" id="telp"
                    value="{{ old('telp', $user->telp) }}">
                  @error('telp')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="alamat">Alamat (opsional)</label>
                <textarea name="alamat" id="alamat" cols="30" rows="10"
                  class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="foto">Foto (opsional)</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror"
                  value="{{ old('foto', $user->foto) }}" aria-describedby="foto-help" accept="image/*">
                @if ($user->foto)
                <small id="foto-help" class="form-text text-muted">Kosongkan saja jika tidak ingin diubah.</small>
                @endif
                @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          @if ($user->foto != null)
          <div class="row">
            <div class="col-md-3 col-sm-6">
              <div class="chocolat-parent">
                <a href="{{ asset('storage/uploads/' . $user->foto) }}" class="chocolat-image"
                  title="{{ $user->nama }}">
                  <div data-crop-image="h-100">
                    <img alt="image" src="{{ asset('storage/uploads/' . $user->foto) }}"
                      class="img-fluid img-thumbnail w-100">
                  </div>
                </a>
              </div>
            </div>
          </div>
          @endif
        </div>
        <div class="card-footer float-right">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
          </button>
          <button type="reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Reset
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection