@extends('layouts.app')

@section('title', 'Tambah Stok')

@section('content')
<section class="section">
  <div class="section-header">
    <div class="section-header-back">
      <a href="{{ url('admin/stokbahan') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
      </a>
    </div>
    <h1>Data Stok</h1>
  </div>
  @if (session('error'))
  <div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
      <div class="alert-title">GAGAL !</div>
      <button class="close" data-dismiss="alert">
        <span>&times;</span>
      </button>
      <p>
        @foreach (session('error') as $error)
        <span class="bullet"></span>&nbsp;{{ strtoupper($error) }}
        <br>
        @endforeach
      </p>
    </div>
  </div>
  @endif
  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Tambah Stok</h4>
      </div>
      <form action="{{ url('admin/stokbahan') }}" method="POST" autocomplete="off">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="bahan_id">Nama Bahan *</label>
                <select class="form-control select2" id="bahan_id" name="bahan_id" onchange="bahan()">
                  @foreach ($bahans as $bahan)
                  <option value="{{ $bahan->id }}" {{ old('bahan_id')==$bahan->id ? 'selected' : '' }}>{{
                    $bahan->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="satuan_id">Satuan *</label>
                <select name="satuan_id" id="satuan_id" class="form-control">
                  <option value="">- Pilih Satuan -</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="stok">Stok *</label>
                <input type="number" name="stok" id="stok" class="form-control" value="{{ old('stok') }}"
                  oninput="this.value = !!this.value && Math.abs(this.value) >= 1 && !!this.value ? Math.abs(this.value) : null">
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer float-right mr-1">
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
<script>
  var bahan_id = document.getElementById('bahan_id');

  selectOption(bahan_id.value);

  function bahan() {
    selectOption(bahan_id.value);
  }

  function selectOption(params) {
      $.ajax({
        url: "{{ url('admin/stokbahan/satuan') }}" + "/" + params,
        type: "GET",
        dataType: "json",
        success: function(data) {
          // console.log(data);
          $('select[name="satuan_id"]').empty();
          if (data.satuan.kategori == "volume") {
            $('select[name="satuan_id"]').append("<option value='1'>Liter</option>\
            <option value='2'>MiliLiter</option>");
            $('select[name="satuan_id"]').val(data.satuan_id).attr('selected', true);
          } else {
            $('select[name="satuan_id"]').append("<option value='3'>Kilogram</option>\
            <option value='4'>Gram</option>\
            <option value='5'>MiliGram</option>");
            $('select[name="satuan_id"]').val(data.satuan_id).attr('selected', true);
          }
        },
      });
  }
</script>
@endsection