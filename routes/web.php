<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaranController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    return redirect('/');
});

Route::get('/', [\App\Http\Controllers\AuthController::class, 'index']);
Route::get('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login_proses']);
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth');

Route::get('berita/{tanggal}/{slug}', [HomeController::class, 'berita']);

Route::get('absen', [AbsenController::class, 'index']);
Route::post('absen', [AbsenController::class, 'store']);
Route::get('absen/scan', [AbsenController::class, 'scan']);
Route::post('absen/scan', [AbsenController::class, 'scan_proses']);

Route::middleware('auth')->group(function () {
    Route::put('profile', [\App\Http\Controllers\AuthController::class, 'update_profile']);
    Route::put('password', [\App\Http\Controllers\AuthController::class, 'update_password']);
    // 
    Route::resource('saran', SaranController::class);
    // 
    Route::post('anggota-get', [\App\Http\Controllers\HomeController::class, 'anggota_get']);
});

Route::middleware('dev')->group(function () {
    Route::get('dev', [\App\Http\Controllers\Dev\DashboardController::class, 'index']);
    Route::get('dev/hubungi/{id}', [\App\Http\Controllers\Dev\DashboardController::class, 'hubungi']);

    Route::get('dev/peminjaman/hapus_draft', [\App\Http\Controllers\Dev\PeminjamanController::class, 'hapus_draft']);
    Route::resource('dev/peminjaman', \App\Http\Controllers\Dev\PeminjamanController::class)->only(['index', 'show', 'destroy']);

    Route::get('dev/user/export', [\App\Http\Controllers\Dev\UserController::class, 'export']);
    Route::post('dev/user/import', [\App\Http\Controllers\Dev\UserController::class, 'import']);
    Route::get('dev/user/trash', [\App\Http\Controllers\Dev\UserController::class, 'trash']);
    Route::get('dev/user/trash/{id}', [\App\Http\Controllers\Dev\UserController::class, 'trash_show']);
    Route::get('dev/user/restore/{id?}', [\App\Http\Controllers\Dev\UserController::class, 'restore']);
    Route::get('dev/user/delete/{id?}', [\App\Http\Controllers\Dev\UserController::class, 'delete']);
    Route::post('dev/user/aktivasi', [\App\Http\Controllers\Dev\UserController::class, 'aktivasi']);
    Route::get('dev/user/refresh-user', [\App\Http\Controllers\Dev\UserController::class, 'refresh_user']);
    Route::get('dev/user/reset_password/{id}', [\App\Http\Controllers\Dev\UserController::class, 'reset_password']);
    Route::resource('dev/user', \App\Http\Controllers\Dev\UserController::class);

    Route::resource('dev/prodi', \App\Http\Controllers\Dev\ProdiController::class);

    Route::resource('dev/subprodi', \App\Http\Controllers\Dev\SubProdiController::class)->except('show');

    Route::resource('dev/tempat', \App\Http\Controllers\Dev\TempatController::class)->except('show');

    Route::resource('dev/ruang', \App\Http\Controllers\Dev\RuangController::class);

    Route::get('dev/barang/trash', [\App\Http\Controllers\Dev\BarangController::class, 'trash']);
    Route::get('dev/barang/restore/{id?}', [\App\Http\Controllers\Dev\BarangController::class, 'restore']);
    Route::get('dev/barang/delete/{id?}', [\App\Http\Controllers\Dev\BarangController::class, 'delete']);
    Route::resource('dev/barang', \App\Http\Controllers\Dev\BarangController::class);

    Route::get('dev/bahan/trash', [\App\Http\Controllers\Dev\BahanController::class, 'trash']);
    Route::get('dev/bahan/restore/{id?}', [\App\Http\Controllers\Dev\BahanController::class, 'restore']);
    Route::get('dev/bahan/delete/{id?}', [\App\Http\Controllers\Dev\BahanController::class, 'delete']);
    Route::resource('dev/bahan', \App\Http\Controllers\Dev\BahanController::class);

    Route::resource('dev/kuesioner', \App\Http\Controllers\Dev\KuesionerController::class);
    Route::resource('dev/pertanyaan-kuesioner', \App\Http\Controllers\Dev\PertanyaanKuesionerController::class);

    Route::resource('dev/praktik', \App\Http\Controllers\Dev\PraktikController::class);

    Route::resource('dev/satuan', \App\Http\Controllers\Dev\SatuanController::class);

    Route::resource('dev/saran', \App\Http\Controllers\Dev\SaranController::class);

    Route::resource('dev/tahun', \App\Http\Controllers\Dev\TahunController::class);
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('add_item/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'add_item']);
    Route::get('search_items', [\App\Http\Controllers\Admin\DashboardController::class, 'search_items']);
    Route::get('tamu-set/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'tamu_set']);
    Route::get('search_tamus', [\App\Http\Controllers\Admin\DashboardController::class, 'search_tamus']);
    Route::get('ruang-set/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'ruang_set']);
    Route::get('ruang-search', [\App\Http\Controllers\Admin\DashboardController::class, 'ruang_search']);
    Route::get('hubungi_tamu/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'hubungi_tamu']);
    Route::get('hubungi_user/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'hubungi_user']);
    Route::get('form-peminjaman-lab', [\App\Http\Controllers\Admin\DashboardController::class, 'form_peminjaman_lab']);
    Route::get('form-jurnal-praktikum', [\App\Http\Controllers\Admin\DashboardController::class, 'form_jurnal_praktikum']);
    Route::get('form-rekap-jurnal', [\App\Http\Controllers\Admin\DashboardController::class, 'form_rekap_jurnal']);

    Route::get('bahan-cari', [\App\Http\Controllers\Admin\DashboardController::class, 'bahan_cari']);
    Route::get('bahan-tambah/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'bahan_tambah']);

    // Buat Peminjaman
    Route::resource('buat', \App\Http\Controllers\Admin\BuatController::class)->only('index', 'store');
    Route::resource('proses', \App\Http\Controllers\Admin\ProsesController::class)->only('index', 'show', 'update', 'destroy');
    Route::resource('riwayat', \App\Http\Controllers\Admin\RiwayatController::class)->only('index', 'show');
    Route::resource('tagihan', \App\Http\Controllers\Admin\TagihanController::class)->only('index', 'show', 'update');
    // Pengguna
    // Mahasiswa
    Route::get('mahasiswa/export', [\App\Http\Controllers\Admin\MahasiswaController::class, 'export']);
    Route::post('mahasiswa/import', [\App\Http\Controllers\Admin\MahasiswaController::class, 'import']);
    Route::get('mahasiswa/ubah_tingkat', [\App\Http\Controllers\Admin\MahasiswaController::class, 'ubah_tingkat']);
    Route::post('mahasiswa/ubah_tingkat_proses', [\App\Http\Controllers\Admin\MahasiswaController::class, 'ubah_tingkat_proses']);
    Route::get('mahasiswa/reset_password/{id}', [\App\Http\Controllers\Admin\MahasiswaController::class, 'reset_password']);
    Route::resource('mahasiswa', \App\Http\Controllers\Admin\MahasiswaController::class);
    // Laboran
    Route::get('laboran/reset_password/{id}', [\App\Http\Controllers\Admin\LaboranController::class, 'reset_password']);
    Route::resource('laboran', \App\Http\Controllers\Admin\LaboranController::class);
    // Tamu
    Route::resource('tamu', \App\Http\Controllers\Admin\TamuController::class);
    // Barang
    Route::get('barang-normal', [\App\Http\Controllers\Admin\BarangController::class, 'normal']);
    Route::get('barang-rusak', [\App\Http\Controllers\Admin\BarangController::class, 'rusak']);
    Route::get('barang/export', [\App\Http\Controllers\Admin\BarangController::class, 'export']);
    Route::post('barang/import', [\App\Http\Controllers\Admin\BarangController::class, 'import']);
    Route::post('barang/import-kode', [\App\Http\Controllers\Admin\BarangController::class, 'import_kode']);
    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class);
    // Bahan
    Route::get('bahan/export', [\App\Http\Controllers\Admin\BahanController::class, 'export']);
    Route::post('bahan/import', [\App\Http\Controllers\Admin\BahanController::class, 'import']);
    Route::post('bahan/cetak/{id}', [\App\Http\Controllers\Admin\BahanController::class, 'cetak']);
    Route::get('bahan/kode-perbarui/{id}', [\App\Http\Controllers\Admin\BahanController::class, 'kode_perbarui']);
    Route::get('bahan/pengeluaran', [\App\Http\Controllers\Admin\BahanController::class, 'pengeluaran']);
    Route::get('bahan/pengeluaran', [\App\Http\Controllers\Admin\BahanController::class, 'pengeluaran']);
    Route::resource('bahan', \App\Http\Controllers\Admin\BahanController::class);

    Route::get('bahan-scan/tambah/{kode}', [\App\Http\Controllers\Admin\BahanScanController::class, 'scan_tambah']);
    Route::resource('bahan-scan', \App\Http\Controllers\Admin\BahanScanController::class);

    Route::get('bahan-pemasukan/manual', [\App\Http\Controllers\Admin\BahanPemasukanController::class, 'create_manual']);
    Route::post('bahan-pemasukan/manual', [\App\Http\Controllers\Admin\BahanPemasukanController::class, 'store_manual']);

    Route::get('bahan-pemasukan/scan', [\App\Http\Controllers\Admin\BahanPemasukanController::class, 'create_scan']);
    Route::post('bahan-pemasukan/scan', [\App\Http\Controllers\Admin\BahanPemasukanController::class, 'store_scan']);

    Route::resource('bahan-pemasukan', \App\Http\Controllers\Admin\BahanPemasukanController::class);

    Route::resource('bahan-pengeluaran', \App\Http\Controllers\Admin\BahanPengeluaranController::class);

    // Trash
    Route::get('user/export', [\App\Http\Controllers\Admin\UserController::class, 'export']);
    Route::post('user/import', [\App\Http\Controllers\Admin\UserController::class, 'import']);
    Route::get('user/reset-password/{id}', [\App\Http\Controllers\Admin\UserController::class, 'reset_password']);
    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('kalab', \App\Http\Controllers\Admin\KalabController::class);

    Route::get('peminjam/export', [\App\Http\Controllers\Admin\PeminjamController::class, 'export']);
    Route::post('peminjam/import', [\App\Http\Controllers\Admin\PeminjamController::class, 'import']);
    Route::resource('peminjam', \App\Http\Controllers\Admin\PeminjamController::class);
    Route::get('exportpeminjam', [\App\Http\Controllers\Admin\PeminjamController::class, 'exportpeminjam']);

    Route::get('pengguna/mahasiswa/export', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'export']);
    Route::post('pengguna/mahasiswa/import', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'import']);
    Route::get('pengguna/mahasiswa/ubah_tingkat', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'ubah_tingkat']);
    Route::post('pengguna/mahasiswa/ubah_tingkat_proses', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'ubah_tingkat_proses']);
    Route::resource('pengguna/mahasiswa', \App\Http\Controllers\Admin\Pengguna\MahasiswaController::class);
    Route::get('exportmahasiswa', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'exportpeminjam']);

    Route::get('pengguna/laboran/reset_password/{id}', [\App\Http\Controllers\Admin\Pengguna\LaboranController::class, 'reset_password']);
    Route::resource('pengguna/laboran', \App\Http\Controllers\Admin\Pengguna\LaboranController::class);

    Route::resource('pengguna/tamu', \App\Http\Controllers\Admin\Pengguna\TamuController::class);

    Route::resource('ruang', \App\Http\Controllers\Admin\RuangController::class);

    Route::get('stokbarang/export', [\App\Http\Controllers\Admin\StokBarangController::class, 'export']);
    Route::post('stokbarang/import', [\App\Http\Controllers\Admin\StokBarangController::class, 'import']);
    Route::resource('stokbarang', \App\Http\Controllers\Admin\StokBarangController::class);

    Route::get('stokbahan/satuan/{id}', [\App\Http\Controllers\Admin\StokBahanController::class, 'satuan']);
    Route::resource('stokbahan', \App\Http\Controllers\Admin\StokBahanController::class);

    Route::get('pengambilan/ruang/{id}', [\App\Http\Controllers\Admin\PengambilanController::class, 'ruang']);
    Route::get('pengambilan/pilih', [\App\Http\Controllers\Admin\PengambilanController::class, 'pilih']);
    Route::resource('pengambilan', \App\Http\Controllers\Admin\PengambilanController::class);
});

Route::middleware('kalab')->group(function () {
    Route::get('kalab', [\App\Http\Controllers\Kalab\DashboardController::class, 'index']);
    Route::get('kalab/hubungi_tamu/{id}', [\App\Http\Controllers\Kalab\DashboardController::class, 'hubungi_tamu']);
    Route::get('kalab/hubungi_user/{id}', [\App\Http\Controllers\Kalab\DashboardController::class, 'hubungi_user']);
    // Menu 1
    Route::resource('kalab/laboran', \App\Http\Controllers\Kalab\LaboranController::class)->only('index', 'show');
    Route::resource('kalab/mahasiswa', \App\Http\Controllers\Kalab\MahasiswaController::class)->only('index', 'show');
    Route::resource('kalab/tamu', \App\Http\Controllers\Kalab\TamuController::class)->only('index', 'show');
    // Menu 2
    // Barang
    Route::get('kalab/barang/hilang', [\App\Http\Controllers\Kalab\BarangController::class, 'hilang']);
    Route::get('kalab/barang/rusak', [\App\Http\Controllers\Kalab\BarangController::class, 'rusak']);
    Route::get('kalab/barang/rusak/unduh', [\App\Http\Controllers\Kalab\BarangController::class, 'unduh']);
    Route::resource('kalab/barang', \App\Http\Controllers\Kalab\BarangController::class)->only('index', 'show');
    // Bahan
    Route::resource('kalab/bahan', \App\Http\Controllers\Kalab\BahanController::class);
    // Ruang
    Route::resource('kalab/ruang', \App\Http\Controllers\Kalab\RuangController::class)->only('index', 'show');

    Route::get('kalab/masuk', [\App\Http\Controllers\Kalab\DashboardController::class, 'masuk']);
    Route::get('kalab/masuk/detail/{id}', [\App\Http\Controllers\Kalab\DashboardController::class, 'masuk_detail']);

    Route::resource('kalab/stokbarang', \App\Http\Controllers\Kalab\StokBarangController::class)->only('index', 'show');
    Route::resource('kalab/stokbahan', \App\Http\Controllers\Kalab\StokBahanController::class)->only('index', 'show');

    Route::resource('kalab/barangrusak', \App\Http\Controllers\Kalab\BarangRusakController::class)->only('index', 'show');
    Route::resource('kalab/baranghilang', \App\Http\Controllers\Kalab\BarangHilangController::class)->only('index', 'show');

    Route::get('kalab/grafik-pengunjung', [\App\Http\Controllers\Kalab\GrafikController::class, 'pengunjung']);
    Route::get('kalab/grafik-ruang', [\App\Http\Controllers\Kalab\GrafikController::class, 'ruang']);
    Route::get('kalab/grafik-barang', [\App\Http\Controllers\Kalab\GrafikController::class, 'barang']);
    Route::get('kalab/grafik-ruang/print', [\App\Http\Controllers\Kalab\GrafikController::class, 'print_ruang']);
    Route::get('kalab/grafik-barang/print', [\App\Http\Controllers\Kalab\GrafikController::class, 'print_barang']);
    // 
    Route::get('kalab/kuesioner', [\App\Http\Controllers\Kalab\KuesionerController::class, 'index']);
    Route::get('kalab/kuesioner/{id}/{tahun}', [\App\Http\Controllers\Kalab\KuesionerController::class, 'show']);
    Route::get('kalab/kuesioner/download/{id}/{tahun}', [\App\Http\Controllers\Kalab\KuesionerController::class, 'download']);
    Route::get('kalab/kuesioner/grafik/{id}/{tahun}', [\App\Http\Controllers\Kalab\KuesionerController::class, 'grafik']);
    Route::post('kalab/kuesioner/pertanyaan', [\App\Http\Controllers\Kalab\KuesionerController::class, 'pertanyaan']);
    // Route::resource('kalab/pertanyaan-kuesioner', \App\Http\Controllers\Kalab\PertanyaanKuesionerController::class);
    Route::get('kalab/absen', [\App\Http\Controllers\Kalab\AbsenController::class, 'index']);

    // Route::resource('kalab/berita', \App\Http\Controllers\Kalab\BeritaController::class);

    Route::get('kalab/arsip/unduh/{id}', [\App\Http\Controllers\Kalab\ArsipController::class, 'unduh']);
    Route::resource('kalab/arsip', \App\Http\Controllers\Kalab\ArsipController::class);
});

Route::middleware('laboran')->prefix('laboran')->group(function () {
    Route::get('/', [\App\Http\Controllers\Laboran\HomeController::class, 'index']);
    Route::put('profile', [\App\Http\Controllers\Laboran\HomeController::class, 'update_profile']);
    Route::put('password', [\App\Http\Controllers\Laboran\HomeController::class, 'update_password']);
    Route::get('hubungi/{id}', [\App\Http\Controllers\Laboran\HomeController::class, 'hubungi']);

    Route::middleware('bidan')->prefix('bidan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Laboran\Bidan\HomeController::class, 'index']);

        Route::get('peminjaman/setujui/{id}', [\App\Http\Controllers\Laboran\Bidan\PeminjamanController::class, 'setujui']);
        Route::resource('peminjaman', \App\Http\Controllers\Laboran\Bidan\PeminjamanController::class)->only('index', 'show', 'destroy');

        Route::resource('pengembalian', \App\Http\Controllers\Laboran\Bidan\PengembalianController::class);

        Route::resource('riwayat', \App\Http\Controllers\Laboran\Bidan\RiwayatController::class);

        Route::resource('tagihan', \App\Http\Controllers\Laboran\Bidan\TagihanController::class)->only('index', 'show', 'update');

        Route::post('laporan/print', [\App\Http\Controllers\Laboran\Bidan\LaporanController::class, 'print']);
        Route::resource('laporan', \App\Http\Controllers\Laboran\Bidan\LaporanController::class)->only('index', 'show');
    });

    Route::middleware('perawat')->prefix('perawat')->group(function () {
        Route::get('/', [\App\Http\Controllers\Laboran\Perawat\HomeController::class, 'index']);

        Route::get('peminjaman/setujui/{id}', [\App\Http\Controllers\Laboran\Perawat\PeminjamanController::class, 'setujui']);
        Route::resource('peminjaman', \App\Http\Controllers\Laboran\Perawat\PeminjamanController::class)->only('index', 'show', 'destroy');

        Route::resource('pengembalian', \App\Http\Controllers\Laboran\Perawat\PengembalianController::class);

        Route::resource('riwayat', \App\Http\Controllers\Laboran\Perawat\RiwayatController::class);

        Route::resource('tagihan', \App\Http\Controllers\Laboran\Perawat\TagihanController::class)->only('index', 'show', 'update');
    });

    Route::middleware('k3')->prefix('k3')->group(function () {
        Route::get('/', [\App\Http\Controllers\Laboran\K3\HomeController::class, 'index']);

        Route::get('peminjaman/setujui/{id}', [\App\Http\Controllers\Laboran\K3\PeminjamanController::class, 'setujui']);
        Route::resource('peminjaman', \App\Http\Controllers\Laboran\K3\PeminjamanController::class)->only('index', 'show', 'destroy');

        Route::resource('pengembalian', \App\Http\Controllers\Laboran\K3\PengembalianController::class);

        Route::resource('riwayat', \App\Http\Controllers\Laboran\K3\RiwayatController::class);

        Route::resource('tagihan', \App\Http\Controllers\Laboran\K3\TagihanController::class)->only('index', 'show', 'update');

        Route::post('laporan/print', [\App\Http\Controllers\Laboran\K3\LaporanController::class, 'print']);
        Route::resource('laporan', \App\Http\Controllers\Laboran\K3\LaporanController::class)->only('index', 'show');
    });

    Route::middleware('farmasi')->prefix('farmasi')->group(function () {
        Route::get('/', [\App\Http\Controllers\Laboran\Farmasi\HomeController::class, 'index']);

        Route::get('peminjaman/setujui/{id}', [\App\Http\Controllers\Laboran\Farmasi\PeminjamanController::class, 'setujui']);
        Route::resource('peminjaman', \App\Http\Controllers\Laboran\Farmasi\PeminjamanController::class)->only('index', 'show', 'destroy');

        Route::put('pengembalian/update-mandiri/{id}', [\App\Http\Controllers\Laboran\Farmasi\PengembalianController::class, 'update_mandiri']);
        Route::put('pengembalian/update-estafet/{id}', [\App\Http\Controllers\Laboran\Farmasi\PengembalianController::class, 'update_estafet']);
        Route::resource('pengembalian', \App\Http\Controllers\Laboran\Farmasi\PengembalianController::class)->only('index', 'show', 'update');

        Route::resource('riwayat', \App\Http\Controllers\Laboran\Farmasi\RiwayatController::class)->only('index', 'show', 'destroy');

        Route::resource('tagihan', \App\Http\Controllers\Laboran\Farmasi\TagihanController::class)->only('index', 'show', 'update');
    });

    // Lab Terpadu
    // Peminjaman Menunggu
    Route::get('laboran/peminjaman-new/setujui/{id}', [\App\Http\Controllers\Laboran\PeminjamanNewController::class, 'setujui']);
    Route::resource('laboran/peminjaman-new', \App\Http\Controllers\Laboran\PeminjamanNewController::class)->only('index', 'show', 'destroy');
    // Dalam Peminjaman
    Route::get('laboran/pengembalian-new/{id}/konfirmasi', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'konfirmasi']);
    Route::post('laboran/pengembalian-new/{id}/p_konfirmasi', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'p_konfirmasi']);
    Route::post('laboran/pengembalian-new/{id}/update', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'update']);
    Route::get('laboran/pengembalian-new/{id}/cetak', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'cetak']);
    Route::get('laboran/pengembalian-new/{id}/hubungi', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'hubungi']);
    Route::get('laboran/pengembalian-new/feb/{id}', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'feb']);
    Route::resource('laboran/pengembalian-new', \App\Http\Controllers\Laboran\PengembalianNewController::class);
    // Riwayat Peminjaman
    Route::resource('laboran/riwayat-new', \App\Http\Controllers\Laboran\RiwayatNewController::class);
    // Tagihan Peminjaman

    // Laporan Peminjaman
    Route::get('laboran/laporan/print', [\App\Http\Controllers\Laboran\LaporanController::class, 'print']);
    Route::post('laboran/laporan/print-farmasi', [\App\Http\Controllers\Laboran\LaporanController::class, 'print_farmasi']);
    Route::post('laboran/laporan/print-lab', [\App\Http\Controllers\Laboran\LaporanController::class, 'print_lab']);
    Route::resource('laboran/laporan', \App\Http\Controllers\Laboran\LaporanController::class)->only('index', 'show');

    // Lab Farmasi
    // Peminjaman Menunggu
    Route::get('laboran/peminjaman/setujui/{id}', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'setujui']);
    Route::resource('laboran/peminjaman', \App\Http\Controllers\Laboran\PeminjamanController::class)->only('index', 'show', 'destroy');
    // Dalam Peminjaman
    Route::put('laboran/pengembalian/update-mandiri/{id}', [\App\Http\Controllers\Laboran\PengembalianController::class, 'update_mandiri']);
    Route::put('laboran/pengembalian/update-estafet/{id}', [\App\Http\Controllers\Laboran\PengembalianController::class, 'update_estafet']);
    Route::resource('laboran/pengembalian', \App\Http\Controllers\Laboran\PengembalianController::class)->only('index', 'show');
    // Riwayat Peminjaman
    Route::resource('laboran/riwayat', \App\Http\Controllers\Laboran\RiwayatController::class)->only('index', 'show', 'destroy');
    // Tagihan Peminjaman - (Sama)

    // Route::post('laboran/pengembalian/{id}/p_konfirmasi', [\App\Http\Controllers\Laboran\PengembalianController::class, 'p_konfirmasi']);
    // Route::post('laboran/pengembalian/{id}/update', [\App\Http\Controllers\Laboran\PengembalianController::class, 'update']);
    // Route::get('laboran/pengembalian/{id}/cetak', [\App\Http\Controllers\Laboran\PengembalianController::class, 'cetak']);
    // Route::get('laboran/pengembalian/hubungi/{id}', [\App\Http\Controllers\Laboran\PengembalianController::class, 'hubungi']);



    // Route::get('laboran/pilih', [\App\Http\Controllers\Laboran\PinjamController::class, 'pilih']);
    // Route::get('laboran/pinjam/riwayat/{id?}', [\App\Http\Controllers\Laboran\PinjamController::class, 'riwayat']);
    // Route::resource('laboran/pinjam', \App\Http\Controllers\Laboran\PinjamController::class);
    // Route::resource('laboran/kelompok', \App\Http\Controllers\Laboran\KelompokController::class);

    // Route::get('laboran/bahan/ruang/{id?}', [\App\Http\Controllers\Laboran\BahanController::class, 'ruang']);
    // Route::get('laboran/bahan/pilih', [\App\Http\Controllers\Laboran\BahanController::class, 'pilih']);
    // Route::resource('laboran/bahan', \App\Http\Controllers\Laboran\BahanController::class);

    // Route::get('laboran/pinjam/{id}/konfirmasi', [\App\Http\Controllers\Laboran\PinjamController::class, 'konfirmasi']);

    // Route::post('laboran/pinjam/submit', [\App\Http\Controllers\Laboran\PinjamController::class, 'submit']);
    // Route::resource('laboran/pinjam', \App\Http\Controllers\Laboran\PinjamController::class);

    // Route::get('laboran/peminjaman/{id}/tolak', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'tolak']);


    // Route::resource('laboran/peminjaman-new/praktik-laboratorium', \App\Http\Controllers\Laboran\Peminjaman\LaboratoriumController::class);
    // Route::resource('laboran/peminjaman-new/praktik-kelas', \App\Http\Controllers\Laboran\Peminjaman\KelasController::class);
    // Route::resource('laboran/peminjaman-new/praktik-luar', \App\Http\Controllers\Laboran\Peminjaman\LuarController::class);
    // Route::get('laboran/pilih/{id}', [\App\Http\Controllers\LaboranController::class, 'peminjaman_detail']);

    // Route::resource('laboran/tagihan/praktik-laboratorium', \App\Http\Controllers\Laboran\Tagihan\LaboratoriumController::class);
    // Route::resource('laboran/tagihan/praktik-kelas', \App\Http\Controllers\Laboran\Tagihan\KelasController::class);
    // Route::resource('laboran/tagihan/praktik-luar', \App\Http\Controllers\Laboran\Tagihan\LuarController::class);

    // Route::get('laboran/tagihan/{id}', [\App\Http\Controllers\Laboran\TagihanController::class, 'show']);
    // Route::get('laboran/tagihan/hubungi/{id}', [\App\Http\Controllers\Laboran\TagihanController::class, 'hubungi']);


    // Route::get('laboran/laporan/{id}', [\App\Http\Controllers\Laboran\LaporanController::class, 'show']);

    // Lab Terpadu

    // Farmasi

    // Route::get('laboran/kelompok/peminjaman/konfirmasi_setuju/{id}', [\App\Http\Controllers\Laboran\KelompokPeminjamanController::class, 'konfirmasi_setuju']);
    // Route::resource('laboran/kelompok/peminjaman', \App\Http\Controllers\Laboran\KelompokPeminjamanController::class);

    // Route::post('laboran/kelompok/pengembalian/konfirmasi_pengembalian/{id}', [\App\Http\Controllers\Laboran\KelompokPengembalianController::class, 'konfirmasi_pengembalian']);
    // Route::resource('laboran/kelompok/pengembalian', \App\Http\Controllers\Laboran\KelompokPengembalianController::class);

    // Route::resource('laboran/kelompok/riwayat', \App\Http\Controllers\Laboran\KelompokRiwayatController::class);
});

Route::middleware('peminjam')->prefix('peminjam')->group(function () {
    Route::get('/', [\App\Http\Controllers\Peminjam\DashboardController::class, 'index']);
    Route::put('peminjam/profile', [\App\Http\Controllers\Peminjam\DashboardController::class, 'update_profile']);
    Route::put('peminjam/password', [\App\Http\Controllers\Peminjam\DashboardController::class, 'update_password']);
    Route::get('peminjam/add_item/{id}', [\App\Http\Controllers\Peminjam\DashboardController::class, 'add_item']);
    Route::get('peminjam/search_items', [\App\Http\Controllers\Peminjam\DashboardController::class, 'search_items']);
    Route::get('peminjam/add_anggota/{id}', [\App\Http\Controllers\Peminjam\DashboardController::class, 'add_anggota']);
    Route::get('peminjam/delete_item/{id}', [\App\Http\Controllers\Peminjam\DashboardController::class, 'delete_item']);
    Route::get('peminjam/anggota-search', [\App\Http\Controllers\Peminjam\DashboardController::class, 'anggota_search']);
    Route::get('peminjam/search-farmasi', [\App\Http\Controllers\Peminjam\DashboardController::class, 'search_farmasi']);
    Route::get('peminjam/barang-get/{id}', [\App\Http\Controllers\Peminjam\DashboardController::class, 'barang_get']);

    Route::get('kuesioner', [\App\Http\Controllers\Peminjam\KuesionerController::class, 'index']);
    Route::get('kuesioner/{id}', [\App\Http\Controllers\Peminjam\KuesionerController::class, 'create']);
    Route::post('kuesioner/{id}', [\App\Http\Controllers\Peminjam\KuesionerController::class, 'store']);

    Route::get('suratbebas', [\App\Http\Controllers\Peminjam\SuratbebasController::class, 'index']);
    Route::get('suratbebas/cetak', [\App\Http\Controllers\Peminjam\SuratbebasController::class, 'cetak']);

    Route::middleware('bidan')->prefix('bidan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Peminjam\Bidan\HomeController::class, 'index']);
        Route::get('barang-cari', [\App\Http\Controllers\Peminjam\Bidan\HomeController::class, 'barang_cari']);
        Route::get('barang-tambah/{id}', [\App\Http\Controllers\Peminjam\Bidan\HomeController::class, 'barang_tambah']);
        Route::get('anggota-cari', [\App\Http\Controllers\Peminjam\Bidan\HomeController::class, 'anggota_cari']);
        Route::post('anggota-tambah', [\App\Http\Controllers\Peminjam\Bidan\HomeController::class, 'anggota_tambah']);

        Route::get('buat', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'index']);
        Route::get('buat/create', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'create']);

        Route::get('buat/praktik-laboratorium', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'create_praktik_laboratorium']);
        Route::post('buat/praktik-laboratorium', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'store_praktik_laboratorium']);

        Route::get('buat/praktik-kelas', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'create_praktik_kelas']);
        Route::post('buat/praktik-kelas', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'store_praktik_kelas']);

        Route::get('buat/praktik-luar', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'create_praktik_luar']);
        Route::post('buat/praktik-luar', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'store_praktik_luar']);

        Route::get('buat/praktik-ruang', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'create_praktik_ruang']);
        Route::post('buat/praktik-ruang', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'store_praktik_ruang']);

        Route::resource('menunggu', \App\Http\Controllers\Peminjam\Bidan\MenungguController::class)->except('create', 'store');

        Route::resource('proses', \App\Http\Controllers\Peminjam\Bidan\ProsesController::class)->except('create', 'store', 'destroy');

        Route::resource('riwayat', \App\Http\Controllers\Peminjam\Bidan\RiwayatController::class)->only('index', 'show');

        Route::resource('tagihan', \App\Http\Controllers\Peminjam\Bidan\TagihanController::class)->only('index', 'show');
    });

    Route::middleware('perawat')->prefix('perawat')->group(function () {
        Route::get('/', [\App\Http\Controllers\Peminjam\Perawat\HomeController::class, 'index']);
        Route::get('barang-cari', [\App\Http\Controllers\Peminjam\Perawat\HomeController::class, 'barang_cari']);
        Route::get('barang-tambah/{id}', [\App\Http\Controllers\Peminjam\Perawat\HomeController::class, 'barang_tambah']);
        Route::get('bahan-cari', [\App\Http\Controllers\Peminjam\Perawat\HomeController::class, 'bahan_cari']);
        Route::get('bahan-tambah/{id}', [\App\Http\Controllers\Peminjam\Perawat\HomeController::class, 'bahan_tambah']);
        Route::get('anggota-cari', [\App\Http\Controllers\Peminjam\Perawat\HomeController::class, 'anggota_cari']);
        Route::post('anggota-tambah', [\App\Http\Controllers\Peminjam\Perawat\HomeController::class, 'anggota_tambah']);

        Route::get('buat', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'index']);
        Route::get('buat/create', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'create']);

        Route::get('buat/praktik-laboratorium', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'create_praktik_laboratorium']);
        Route::post('buat/praktik-laboratorium', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'store_praktik_laboratorium']);

        Route::get('buat/praktik-kelas', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'create_praktik_kelas']);
        Route::post('buat/praktik-kelas', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'store_praktik_kelas']);

        Route::get('buat/praktik-luar', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'create_praktik_luar']);
        Route::post('buat/praktik-luar', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'store_praktik_luar']);

        Route::get('buat/praktik-ruang', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'create_praktik_ruang']);
        Route::post('buat/praktik-ruang', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'store_praktik_ruang']);

        Route::resource('menunggu', \App\Http\Controllers\Peminjam\Perawat\MenungguController::class)->except('create', 'store');

        Route::resource('proses', \App\Http\Controllers\Peminjam\Perawat\ProsesController::class)->except('create', 'store', 'destroy');

        Route::resource('riwayat', \App\Http\Controllers\Peminjam\Perawat\RiwayatController::class)->only('index', 'show');

        Route::resource('tagihan', \App\Http\Controllers\Peminjam\Perawat\TagihanController::class)->only('index', 'show');
    });

    Route::middleware('k3')->prefix('k3')->group(function () {
        Route::get('/', [\App\Http\Controllers\Peminjam\K3\HomeController::class, 'index']);
        Route::get('barang-cari', [\App\Http\Controllers\Peminjam\K3\HomeController::class, 'barang_cari']);
        Route::get('barang-tambah/{id}', [\App\Http\Controllers\Peminjam\K3\HomeController::class, 'barang_tambah']);
        Route::get('anggota-cari', [\App\Http\Controllers\Peminjam\K3\HomeController::class, 'anggota_cari']);
        Route::post('anggota-tambah', [\App\Http\Controllers\Peminjam\K3\HomeController::class, 'anggota_tambah']);

        Route::get('buat', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'index']);
        Route::get('buat/create', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'create']);

        Route::get('buat/praktik-laboratorium', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'create_praktik_laboratorium']);
        Route::post('buat/praktik-laboratorium', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'store_praktik_laboratorium']);

        Route::get('buat/praktik-kelas', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'create_praktik_kelas']);
        Route::post('buat/praktik-kelas', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'store_praktik_kelas']);

        Route::get('buat/praktik-luar', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'create_praktik_luar']);
        Route::post('buat/praktik-luar', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'store_praktik_luar']);

        Route::get('buat/praktik-ruang', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'create_praktik_ruang']);
        Route::post('buat/praktik-ruang', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'store_praktik_ruang']);

        Route::resource('menunggu', \App\Http\Controllers\Peminjam\K3\MenungguController::class)->except('create', 'store');

        Route::resource('proses', \App\Http\Controllers\Peminjam\K3\ProsesController::class)->except('create', 'store', 'destroy');

        Route::resource('riwayat', \App\Http\Controllers\Peminjam\K3\RiwayatController::class)->only('index', 'show');

        Route::resource('tagihan', \App\Http\Controllers\Peminjam\K3\TagihanController::class)->only('index', 'show');
    });

    Route::middleware('farmasi')->prefix('farmasi')->group(function () {
        Route::get('/', [\App\Http\Controllers\Peminjam\Farmasi\HomeController::class, 'index']);
        Route::get('barang-cari', [\App\Http\Controllers\Peminjam\Farmasi\HomeController::class, 'barang_cari']);
        Route::get('barang-tambah/{id}', [\App\Http\Controllers\Peminjam\Farmasi\HomeController::class, 'barang_tambah']);
        Route::get('estafet-tambah/{id}', [\App\Http\Controllers\Peminjam\Farmasi\HomeController::class, 'estafet_tambah']);
        Route::get('bahan-cari', [\App\Http\Controllers\Peminjam\Farmasi\HomeController::class, 'bahan_cari']);
        Route::get('bahan-tambah/{id}', [\App\Http\Controllers\Peminjam\Farmasi\HomeController::class, 'bahan_tambah']);
        Route::get('anggota-cari', [\App\Http\Controllers\Peminjam\Farmasi\HomeController::class, 'anggota_cari']);
        Route::post('anggota-tambah', [\App\Http\Controllers\Peminjam\Farmasi\HomeController::class, 'anggota_tambah']);

        Route::get('buat/estafet/{id}', [\App\Http\Controllers\Peminjam\Farmasi\BuatController::class, 'create_estafet']);
        Route::post('buat/estafet/{id}', [\App\Http\Controllers\Peminjam\Farmasi\BuatController::class, 'store_estafet']);
        Route::get('buat/mandiri/{id}', [\App\Http\Controllers\Peminjam\Farmasi\BuatController::class, 'create_mandiri']);
        Route::post('buat/mandiri/{id}', [\App\Http\Controllers\Peminjam\Farmasi\BuatController::class, 'store_mandiri']);
        Route::resource('buat', \App\Http\Controllers\Peminjam\Farmasi\BuatController::class);

        Route::resource('menunggu', \App\Http\Controllers\Peminjam\Farmasi\MenungguController::class)->except('create', 'store');
        Route::resource('proses', \App\Http\Controllers\Peminjam\Farmasi\ProsesController::class)->except('create', 'store', 'destroy');
        Route::resource('riwayat', \App\Http\Controllers\Peminjam\Farmasi\RiwayatController::class)->only('index', 'show');
        Route::resource('tagihan', \App\Http\Controllers\Peminjam\Farmasi\TagihanController::class)->only('index', 'show');
    });

    Route::middleware('labterpadu')->group(function () {
        Route::get('peminjam/labterpadu', [\App\Http\Controllers\Peminjam\LabTerpadu\HomeController::class, 'index']);
        // 
        Route::get('peminjam/labterpadu/buat/create-praktik-laboratorium', [\App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class, 'create_praktik_laboratorium']);
        Route::post('peminjam/labterpadu/buat/store-praktik-laboratorium', [\App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class, 'store_praktik_laboratorium']);
        // 
        Route::get('peminjam/labterpadu/buat/create-praktik-kelas', [\App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class, 'create_praktik_kelas']);
        Route::post('peminjam/labterpadu/buat/store-praktik-kelas', [\App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class, 'store_praktik_kelas']);
        // 
        Route::get('peminjam/labterpadu/buat/create-praktik-luar', [\App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class, 'create_praktik_luar']);
        Route::post('peminjam/labterpadu/buat/store-praktik-luar', [\App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class, 'store_praktik_luar']);
        // 
        Route::get('peminjam/labterpadu/buat/create-praktik-ruang', [\App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class, 'create_praktik_ruang']);
        Route::post('peminjam/labterpadu/buat/store-praktik-ruang', [\App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class, 'store_praktik_ruang']);
        // 
        Route::resource('peminjam/labterpadu/buat', \App\Http\Controllers\Peminjam\LabTerpadu\BuatController::class);
        Route::resource('peminjam/labterpadu/menunggu', \App\Http\Controllers\Peminjam\LabTerpadu\MenungguController::class)->except('create', 'store');
        Route::resource('peminjam/labterpadu/proses', \App\Http\Controllers\Peminjam\LabTerpadu\ProsesController::class)->except('create', 'store', 'destroy');
        Route::resource('peminjam/labterpadu/riwayat', \App\Http\Controllers\Peminjam\LabTerpadu\RiwayatController::class)->only('index', 'show');
        Route::resource('peminjam/labterpadu/tagihan', \App\Http\Controllers\Peminjam\LabTerpadu\TagihanController::class)->only('index', 'show');
    });

    Route::middleware('feb')->group(function () {
        Route::get('peminjam/feb', [\App\Http\Controllers\Peminjam\Feb\HomeController::class, 'index']);
        Route::resource('peminjam/feb/buat', \App\Http\Controllers\Peminjam\Feb\BuatController::class);
        Route::resource('peminjam/feb/menunggu', \App\Http\Controllers\Peminjam\Feb\MenungguController::class)->except('create', 'store');
        Route::resource('peminjam/feb/proses', \App\Http\Controllers\Peminjam\Feb\ProsesController::class)->except('create', 'store', 'destroy');
        Route::resource('peminjam/feb/riwayat', \App\Http\Controllers\Peminjam\Feb\RiwayatController::class)->only('index', 'show');
        Route::resource('peminjam/feb/tagihan', \App\Http\Controllers\Peminjam\Feb\TagihanController::class)->only('index', 'show');
    });

    Route::middleware('ti')->group(function () {
        Route::get('peminjam/ti', [\App\Http\Controllers\Peminjam\Ti\HomeController::class, 'index']);
        Route::resource('peminjam/ti/buat', \App\Http\Controllers\Peminjam\Ti\BuatController::class);
        Route::resource('peminjam/ti/menunggu', \App\Http\Controllers\Peminjam\Ti\MenungguController::class)->except('create', 'store');
        Route::resource('peminjam/ti/proses', \App\Http\Controllers\Peminjam\Ti\ProsesController::class)->except('create', 'store', 'destroy');
        Route::resource('peminjam/ti/riwayat', \App\Http\Controllers\Peminjam\Ti\RiwayatController::class)->only('index', 'show');
        Route::resource('peminjam/ti/tagihan', \App\Http\Controllers\Peminjam\Ti\TagihanController::class)->only('index', 'show');
    });

    Route::get('peminjam/check', [\App\Http\Controllers\Peminjam\DashboardController::class, 'check']);
    Route::get('peminjam/pinjam', [\App\Http\Controllers\Peminjam\DashboardController::class, 'pinjam']);
    Route::get('peminjam/pilih', [\App\Http\Controllers\Peminjam\DashboardController::class, 'pilih']);
    Route::post('peminjam/pinjam/proses', [\App\Http\Controllers\Peminjam\DashboardController::class, 'proses']);

    Route::resource('peminjam/normal/peminjaman-new/laboratorium', \App\Http\Controllers\Peminjam\Peminjaman\LaboratoriumController::class)->except('destroy');
    Route::resource('peminjam/normal/peminjaman-new/kelas', \App\Http\Controllers\Peminjam\Peminjaman\KelasController::class)->except('destroy');
    Route::resource('peminjam/normal/peminjaman-new/luar', \App\Http\Controllers\Peminjam\Peminjaman\LuarController::class)->except('destroy');

    Route::post('peminjam/normal/peminjaman-new/praktik', [\App\Http\Controllers\Peminjam\Peminjaman\DashboardController::class, 'praktik']);
    Route::resource('peminjam/normal/peminjaman-new', \App\Http\Controllers\Peminjam\Peminjaman\DashboardController::class)->except('store', 'update');

    Route::get('peminjam/normal/peminjaman-new/create', [\App\Http\Controllers\Peminjam\Peminjaman\Create\IndexController::class, 'index']);
    // Route::get('peminjam/normal/peminjaman-new/create/laboratory', [\App\Http\Controllers\Peminjam\Peminjaman\Create\LaboratoryController::class, 'create']);
    // Route::post('peminjam/normal/peminjaman-new/create/laboratory', [\App\Http\Controllers\Peminjam\Peminjaman\Create\LaboratoryController::class, 'store']);

    // Route::get('peminjam/normal/peminjaman-new/proses', [\App\Http\Controllers\Peminjam\Peminjaman\ProsesController::class, 'index']);

    Route::resource('peminjam/pinjam/kelompok', \App\Http\Controllers\Peminjam\PinjamKelompokController::class);
    // Route::resource('peminjam/kelompok', \App\Http\Controllers\Peminjam\KelompokController::class);

    Route::resource('peminjam/normal/peminjaman/mandiri', \App\Http\Controllers\Peminjam\Farmasi\MandiriController::class)->except('create');
    Route::resource('peminjam/normal/peminjaman/estafet', \App\Http\Controllers\Peminjam\Farmasi\MandiriController::class)->except('create');

    Route::get('peminjam/normal/peminjaman/delete/{id}', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'delete']);
    Route::post('peminjam/normal/peminjaman/create', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'create']);
    Route::resource('peminjam/normal/peminjaman', \App\Http\Controllers\Peminjam\PeminjamanController::class);


    Route::get('peminjam/normal/peminjaman-new/delete/{id}', [\App\Http\Controllers\Peminjam\PeminjamanNewController::class, 'delete']);
    // Route::resource('peminjam/normal/peminjaman-new', \App\Http\Controllers\Peminjam\PeminjamanNewController::class);

    // Route::get('peminjam/normal/peminjaman/{id}', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'show']);
    // Route::get('peminjam/normal/peminjaman/{id}/cetak', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'cetak']);
    // Route::get('peminjam/normal/peminjaman/{id}/batal', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'batal']);

    Route::resource('peminjam/normal/pengembalian', \App\Http\Controllers\Peminjam\PengembalianController::class);

    Route::resource('peminjam/normal/pengembalian-new', \App\Http\Controllers\Peminjam\PengembalianNewController::class);

    Route::get('peminjam/normal/riwayat', [\App\Http\Controllers\Peminjam\RiwayatController::class, 'index']);
    Route::get('peminjam/normal/riwayat/{id}', [\App\Http\Controllers\Peminjam\RiwayatController::class, 'show']);

    Route::get('peminjam/normal/riwayat-new', [\App\Http\Controllers\Peminjam\RiwayatNewController::class, 'index']);
    Route::get('peminjam/normal/riwayat-new/{id}', [\App\Http\Controllers\Peminjam\RiwayatNewController::class, 'show']);

    Route::post('peminjam/estafet/peminjaman/kelompok_create', [\App\Http\Controllers\Peminjam\EstafetPeminjamanController::class, 'kelompok_create']);
    Route::resource('peminjam/estafet/peminjaman', \App\Http\Controllers\Peminjam\EstafetPeminjamanController::class);
    Route::resource('peminjam/estafet/pengembalian', \App\Http\Controllers\Peminjam\EstafetPengembalianController::class);
    Route::resource('peminjam/estafet/riwayat', \App\Http\Controllers\Peminjam\EstafetRiwayatController::class);

    Route::resource('peminjam/tagihan', \App\Http\Controllers\Peminjam\TagihanController::class);

    Route::get('peminjam/tatacara', [\App\Http\Controllers\Peminjam\TatacaraController::class, 'index']);

    Route::prefix('peminjam/farmasi')->group(function () {
        Route::post('buat/create', [\App\Http\Controllers\Peminjam\Farmasi\BuatController::class, 'create']);
        Route::resource('buat', \App\Http\Controllers\Peminjam\Farmasi\BuatController::class)->except('create');

        Route::resource('menunggu', \App\Http\Controllers\Peminjam\Farmasi\MenungguController::class);
        Route::resource('proses', \App\Http\Controllers\Peminjam\Farmasi\ProsesController::class);
        Route::resource('riwayat', \App\Http\Controllers\Peminjam\Farmasi\RiwayatController::class);
        Route::resource('tagihan', \App\Http\Controllers\Peminjam\Farmasi\TagihanController::class);
    });
});

Route::middleware('web')->group(function () {
    Route::get('web', [\App\Http\Controllers\Web\DashboardController::class, 'index']);
    Route::resource('web/berita', \App\Http\Controllers\Web\BeritaController::class);
});
