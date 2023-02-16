@extends('layouts.app')

@section('title', 'Tata Cara')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Tata Cara</h1>
  </div>
  <div class="section-body">
    <div class="row mb-4">
      <div class="col-12">
        <div class="card mb-0">
          <div class="card-body">
            <ul class="nav nav-pills">
              <li class="nav-item mr-2">
                <a class="nav-link active" href="#peminjaman" id="peminjaman-tab" data-toggle="tab" role="tab"
                  aria-controls="peminjaman" aria-selected="true">Peminjaman</a>
              </li>
              <li class="nav-item mr-2">
                <a class="nav-link" href="#pengembalian" id="pengembalian-tab" data-toggle="tab" role="tab"
                  aria-controls="pengembalian" aria-selected="false">Pengembalian</a>
              </li>
              <li class="nav-item mr-2">
                <a class="nav-link" href="#suratbebas" id="suratbebas-tab" data-toggle="tab" role="tab"
                  aria-controls="suratbebas" aria-selected="false">Surat Bebas</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="peminjaman" role="tabpanel" aria-labelledby="peminjaman-tab">
            <div class="activities">
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>Pergi ke halaman "<a href="{{ url('peminjam') }}">Dashboard</a>".</p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>Tekan tombol "<a href="{{ url('peminjam/pinjam') }}">Pinjam Barang</a>".</p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>
                    Isi semua form yang dibutuhkan seperti :
                    <br>
                    <span class="bullet"></span>&nbsp;tanggal pinjam,
                    <br>
                    <span class="bullet"></span>&nbsp;tanggal kembali,
                    <br>
                    <span class="bullet"></span>&nbsp;jam pinjam,
                    <br>
                    <span class="bullet"></span>&nbsp;jam kembali,
                    <br>
                    <span class="bullet"></span>&nbsp;mata kuliah,
                    <br>
                    <span class="bullet"></span>&nbsp;dosen pengampu,
                    <br>
                    <span class="bullet"></span>&nbsp;ruang laboratorium,
                    <br>
                    <span class="bullet"></span>&nbsp;dan keterangan.
                    <br>
                    Lalu masukan barang / bahan yang diperlukan beserta jumlahnya.
                  </p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>
                    Peminjaman telah dibuat.
                    <br>
                    Menunggu konfirmasi dari Laboran.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="pengembalian" role="tabpanel" aria-labelledby="pengembalian-tab">
            <div class="activities">
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>Pergi ke ruang lab yang digunakan.</p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>Kembalikan semua barang-barang yang dipinjam sebelumnya.</p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>Tunggu Laboran dari ruang lab tersebut mengkonfirmasi peminjamannya.</p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>
                    Peminjaman telah dikembalian.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="suratbebas" role="tabpanel" aria-labelledby="suratbebas-tab">
            <div class="activities">
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>Cek peminjaman Anda pada menu peminjaman.</p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail w-50">
                  <p>Jika masih terdapat peminjaman maka surat bebas belum bisa dicetak karena Anda masih punya peminjaman yang belum dikembalikan.</p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail w-50">
                  <p>Apabila Anda merasa sudah mengembalikan barang tersebut coba untuk menghubungi laboran ruang terkait (dapat dilihat dari detail peminjaman tersebut).</p>
                </div>
              </div>
              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="fas fa-home"></i>
                </div>
                <div class="activity-detail">
                  <p>
                    Surat bebas dapat dicetak apabila tidak terdapat tanggungan peminjaman.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function modalBatal(id) {
    $("#batal-" + id).submit();
  }
</script>
@endsection