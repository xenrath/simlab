<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaranController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', [HomeController::class, 'index']);
Route::get('berita/{tanggal}/{slug}', [HomeController::class, 'berita']);

Auth::routes();

Route::get('logout', function () {
    Auth::logout();
    return redirect()->to('/');
});

Route::resource('absen', AbsenController::class);

Route::middleware('auth')->group(function () {

    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile/update', [ProfileController::class, 'update']);

    Route::resource('saran', SaranController::class);

    Route::middleware('dev')->group(function () {
        Route::get('dev', [\App\Http\Controllers\Dev\DashboardController::class, 'index']);

        Route::resource('dev/peminjaman', \App\Http\Controllers\Dev\PeminjamanController::class);

        Route::get('dev/user/export', [\App\Http\Controllers\Dev\UserController::class, 'export']);
        Route::post('dev/user/import', [\App\Http\Controllers\Dev\UserController::class, 'import']);
        Route::get('dev/user/trash', [\App\Http\Controllers\Dev\UserController::class, 'trash']);
        Route::get('dev/user/restore/{id?}', [\App\Http\Controllers\Dev\UserController::class, 'restore']);
        Route::get('dev/user/delete/{id?}', [\App\Http\Controllers\Dev\UserController::class, 'delete']);
        Route::resource('dev/user', \App\Http\Controllers\Dev\UserController::class);

        Route::resource('dev/prodi', \App\Http\Controllers\Dev\ProdiController::class);

        Route::resource('dev/subprodi', \App\Http\Controllers\Dev\SubProdiController::class);

        Route::resource('dev/tempat', \App\Http\Controllers\Dev\TempatController::class);

        Route::get('dev/ruang/prodi/{id}', [\App\Http\Controllers\Dev\RuangController::class, 'prodi']);
        Route::resource('dev/ruang', \App\Http\Controllers\Dev\RuangController::class);

        Route::get('dev/barang/trash', [\App\Http\Controllers\Dev\BarangController::class, 'trash']);
        Route::get('dev/barang/restore/{id?}', [\App\Http\Controllers\Dev\BarangController::class, 'restore']);
        Route::get('dev/barang/delete/{id?}', [\App\Http\Controllers\Dev\BarangController::class, 'delete']);
        Route::post('dev/barang/satuan', [\App\Http\Controllers\Dev\BarangController::class, 'satuan']);
        Route::resource('dev/barang', \App\Http\Controllers\Dev\BarangController::class);

        Route::get('dev/bahan/trash', [\App\Http\Controllers\Dev\BahanController::class, 'trash']);
        Route::get('dev/bahan/restore/{id?}', [\App\Http\Controllers\Dev\BahanController::class, 'restore']);
        Route::get('dev/bahan/delete/{id?}', [\App\Http\Controllers\Dev\BahanController::class, 'delete']);
        Route::post('dev/bahan/satuan', [\App\Http\Controllers\Dev\BahanController::class, 'satuan']);
        Route::resource('dev/bahan', \App\Http\Controllers\Dev\BahanController::class);

        Route::resource('dev/praktik', \App\Http\Controllers\Dev\PraktikController::class);

        Route::resource('dev/satuan', \App\Http\Controllers\Dev\SatuanController::class);
    });

    Route::middleware('admin')->group(function () {
        Route::get('admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

        Route::get('admin/peminjaman/pilih', [\App\Http\Controllers\Admin\PeminjamanController::class, 'pilih']);
        Route::get('admin/peminjaman/konfirmasi_selesai/{id}', [\App\Http\Controllers\Admin\PeminjamanController::class, 'konfirmasi_selesai']);
        Route::resource('admin/peminjaman', \App\Http\Controllers\Admin\PeminjamanController::class);

        Route::get('admin/user/export', [\App\Http\Controllers\Admin\UserController::class, 'export']);
        Route::post('admin/user/import', [\App\Http\Controllers\Admin\UserController::class, 'import']);
        Route::resource('admin/user', \App\Http\Controllers\Admin\UserController::class);

        Route::resource('admin/kalab', \App\Http\Controllers\Admin\KalabController::class);

        Route::get('admin/laboran/export', [\App\Http\Controllers\Admin\LaboranController::class, 'export']);
        Route::post('admin/laboran/import', [\App\Http\Controllers\Admin\LaboranController::class, 'import']);
        Route::resource('admin/laboran', \App\Http\Controllers\Admin\LaboranController::class);

        Route::get('admin/peminjam/export', [\App\Http\Controllers\Admin\PeminjamController::class, 'export']);
        Route::post('admin/peminjam/import', [\App\Http\Controllers\Admin\PeminjamController::class, 'import']);
        Route::resource('admin/peminjam', \App\Http\Controllers\Admin\PeminjamController::class);
        Route::get('admin/exportpeminjam', [\App\Http\Controllers\Admin\PeminjamController::class, 'exportpeminjam']);

        Route::resource('admin/ruang', \App\Http\Controllers\Admin\RuangController::class);

        Route::get('admin/barang/export', [\App\Http\Controllers\Admin\BarangController::class, 'export']);
        Route::post('admin/barang/import', [\App\Http\Controllers\Admin\BarangController::class, 'import']);
        Route::resource('admin/barang', \App\Http\Controllers\Admin\BarangController::class);

        Route::get('admin/bahan/export', [\App\Http\Controllers\Admin\BahanController::class, 'export']);
        Route::post('admin/bahan/import', [\App\Http\Controllers\Admin\BahanController::class, 'import']);
        Route::resource('admin/bahan', \App\Http\Controllers\Admin\BahanController::class);

        Route::resource('admin/stokbarang', \App\Http\Controllers\Admin\StokBarangController::class);

        Route::get('admin/stokbahan/satuan/{id}', [\App\Http\Controllers\Admin\StokBahanController::class, 'satuan']);
        Route::resource('admin/stokbahan', \App\Http\Controllers\Admin\StokBahanController::class);

        Route::get('admin/pengambilan/ruang/{id}', [\App\Http\Controllers\Admin\PengambilanController::class, 'ruang']);
        Route::get('admin/pengambilan/pilih', [\App\Http\Controllers\Admin\PengambilanController::class, 'pilih']);
        Route::resource('admin/pengambilan', \App\Http\Controllers\Admin\PengambilanController::class);
    });

    Route::middleware('kalab')->group(function () {
        Route::get('kalab', [\App\Http\Controllers\Kalab\DashboardController::class, 'index']);

        Route::resource('kalab/admin', \App\Http\Controllers\Kalab\AdminController::class)->only('index', 'show');
        Route::resource('kalab/laboran', \App\Http\Controllers\Kalab\LaboranController::class)->only('index', 'show');
        Route::resource('kalab/peminjam', \App\Http\Controllers\Kalab\PeminjamController::class)->only('index', 'show');
        Route::resource('kalab/ruang', \App\Http\Controllers\Kalab\RuangController::class)->only('index', 'show');

        Route::get('kalab/masuk', [\App\Http\Controllers\Kalab\DashboardController::class, 'masuk']);
        Route::get('kalab/masuk/detail/{id}', [\App\Http\Controllers\Kalab\DashboardController::class, 'masuk_detail']);

        Route::resource('kalab/stokbarang', \App\Http\Controllers\Kalab\StokBarangController::class)->only('index', 'show');
        Route::resource('kalab/stokbahan', \App\Http\Controllers\Kalab\StokBahanController::class)->only('index', 'show');

        Route::resource('kalab/barangrusak', \App\Http\Controllers\Kalab\BarangRusakController::class)->only('index', 'show');
        Route::resource('kalab/baranghilang', \App\Http\Controllers\Kalab\BarangHilangController::class)->only('index', 'show');
        Route::resource('kalab/bahanhabis', \App\Http\Controllers\Kalab\BahanHabisController::class);

        Route::get('kalab/grafik/pengunjung', [\App\Http\Controllers\Kalab\GrafikController::class, 'pengunjung']);
        Route::get('kalab/grafik/ruang', [\App\Http\Controllers\Kalab\GrafikController::class, 'ruang']);
        Route::get('kalab/grafik/barang', [\App\Http\Controllers\Kalab\GrafikController::class, 'barang']);

        Route::get('kalab/absen', [\App\Http\Controllers\Kalab\AbsenController::class, 'index']);

        Route::resource('kalab/berita', \App\Http\Controllers\Kalab\BeritaController::class);
    });

    Route::middleware('laboran')->group(function () {
        Route::get('laboran', [\App\Http\Controllers\Laboran\HomeController::class, 'index']);

        Route::get('laboran/pilih', [\App\Http\Controllers\Laboran\PinjamController::class, 'pilih']);
        Route::get('laboran/pinjam/riwayat/{id?}', [\App\Http\Controllers\Laboran\PinjamController::class, 'riwayat']);
        Route::resource('laboran/pinjam', \App\Http\Controllers\Laboran\PinjamController::class);
        // Route::resource('laboran/kelompok', \App\Http\Controllers\Laboran\KelompokController::class);

        Route::get('laboran/bahan/ruang/{id?}', [\App\Http\Controllers\Laboran\BahanController::class, 'ruang']);
        Route::get('laboran/bahan/pilih', [\App\Http\Controllers\Laboran\BahanController::class, 'pilih']);
        Route::resource('laboran/bahan', \App\Http\Controllers\Laboran\BahanController::class);

        Route::get('laboran/pinjam/{id}/konfirmasi', [\App\Http\Controllers\Laboran\PinjamController::class, 'konfirmasi']);

        Route::post('laboran/pinjam/submit', [\App\Http\Controllers\Laboran\PinjamController::class, 'submit']);
        // Route::resource('laboran/pinjam', \App\Http\Controllers\Laboran\PinjamController::class);

        Route::get('laboran/peminjaman', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'index']);
        Route::get('laboran/peminjaman/{id}', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'show']);
        Route::get('laboran/peminjaman/{id}/setujui', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'setujui']);
        Route::get('laboran/peminjaman/{id}/tolak', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'tolak']);

        Route::get('laboran/peminjaman-new', [\App\Http\Controllers\Laboran\PeminjamanNewController::class, 'index']);
        Route::get('laboran/peminjaman-new/{id}', [\App\Http\Controllers\Laboran\PeminjamanNewController::class, 'show']);
        Route::get('laboran/peminjaman-new/{id}/setujui', [\App\Http\Controllers\Laboran\PeminjamanNewController::class, 'setujui']);
        Route::get('laboran/peminjaman-new/{id}/tolak', [\App\Http\Controllers\Laboran\PeminjamanNewController::class, 'tolak']);

        // Route::get('laboran/pilih/{id}', [\App\Http\Controllers\LaboranController::class, 'peminjaman_detail']);

        // Lab Terpadu

        Route::get('laboran/pengembalian', [\App\Http\Controllers\Laboran\PengembalianController::class, 'index']);
        Route::get('laboran/pengembalian/{id}', [\App\Http\Controllers\Laboran\PengembalianController::class, 'show']);
        Route::get('laboran/pengembalian/{id}/konfirmasi', [\App\Http\Controllers\Laboran\PengembalianController::class, 'konfirmasi']);
        Route::post('laboran/pengembalian/{id}/p_konfirmasi', [\App\Http\Controllers\Laboran\PengembalianController::class, 'p_konfirmasi']);
        Route::post('laboran/pengembalian/{id}/update', [\App\Http\Controllers\Laboran\PengembalianController::class, 'update']);
        Route::get('laboran/pengembalian/{id}/cetak', [\App\Http\Controllers\Laboran\PengembalianController::class, 'cetak']);

        Route::get('laboran/pengembalian-new/{id}/konfirmasi', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'konfirmasi']);
        Route::post('laboran/pengembalian-new/{id}/p_konfirmasi', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'p_konfirmasi']);
        Route::post('laboran/pengembalian-new/{id}/update', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'update']);
        Route::get('laboran/pengembalian-new/{id}/cetak', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'cetak']);
        Route::resource('laboran/pengembalian-new', \App\Http\Controllers\Laboran\PengembalianNewController::class);

        Route::resource('laboran/riwayat', \App\Http\Controllers\Laboran\RiwayatController::class);

        Route::resource('laboran/riwayat-new', \App\Http\Controllers\Laboran\RiwayatNewController::class);

        // Farmasi

        Route::get('laboran/kelompok/peminjaman/konfirmasi_setuju/{id}', [\App\Http\Controllers\Laboran\KelompokPeminjamanController::class, 'konfirmasi_setuju']);
        Route::resource('laboran/kelompok/peminjaman', \App\Http\Controllers\Laboran\KelompokPeminjamanController::class);

        Route::post('laboran/kelompok/pengembalian/konfirmasi_pengembalian/{id}', [\App\Http\Controllers\Laboran\KelompokPengembalianController::class, 'konfirmasi_pengembalian']);
        Route::resource('laboran/kelompok/pengembalian', \App\Http\Controllers\Laboran\KelompokPengembalianController::class);

        Route::resource('laboran/kelompok/riwayat', \App\Http\Controllers\Laboran\KelompokRiwayatController::class);

        Route::get('laboran/rusak', [\App\Http\Controllers\Laboran\RusakController::class, 'index']);
        Route::get('laboran/rusak/{id}', [\App\Http\Controllers\Laboran\RusakController::class, 'show']);
        Route::post('laboran/rusak/{id}/konfirmasi', [\App\Http\Controllers\Laboran\RusakController::class, 'konfirmasi']);
    });

    Route::middleware('peminjam')->group(function () {
        Route::get('peminjam', [\App\Http\Controllers\Peminjam\DashboardController::class, 'index']);
        Route::get('peminjam/check', [\App\Http\Controllers\Peminjam\DashboardController::class, 'check']);
        Route::get('peminjam/pinjam', [\App\Http\Controllers\Peminjam\DashboardController::class, 'pinjam']);
        Route::get('peminjam/pilih', [\App\Http\Controllers\Peminjam\DashboardController::class, 'pilih']);
        Route::post('peminjam/pinjam/proses', [\App\Http\Controllers\Peminjam\DashboardController::class, 'proses']);

        Route::resource('peminjam/pinjam/kelompok', \App\Http\Controllers\Peminjam\PinjamKelompokController::class);
        Route::resource('peminjam/kelompok', \App\Http\Controllers\Peminjam\KelompokController::class);

        Route::get('peminjam/normal/peminjaman/delete/{id}', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'delete']);
        Route::resource('peminjam/normal/peminjaman', \App\Http\Controllers\Peminjam\PeminjamanController::class);

        Route::get('peminjam/normal/peminjaman-new/delete/{id}', [\App\Http\Controllers\Peminjam\PeminjamanNewController::class, 'delete']);
        Route::resource('peminjam/normal/peminjaman-new', \App\Http\Controllers\Peminjam\PeminjamanNewController::class);
        
        // Route::get('peminjam/normal/peminjaman/{id}', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'show']);
        // Route::get('peminjam/normal/peminjaman/{id}/cetak', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'cetak']);
        // Route::get('peminjam/normal/peminjaman/{id}/batal', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'batal']);

        Route::resource('peminjam/normal/pengembalian', \App\Http\Controllers\Peminjam\PengembalianController::class);

        Route::resource('peminjam/normal/pengembalian-new', \App\Http\Controllers\Peminjam\PengembalianNewController::class);

        Route::get('peminjam/normal/riwayat', [\App\Http\Controllers\Peminjam\RiwayatController::class, 'index']);
        Route::get('peminjam/normal/riwayat/{id}', [\App\Http\Controllers\Peminjam\RiwayatController::class, 'show']);

        Route::get('peminjam/normal/riwayat-new', [\App\Http\Controllers\Peminjam\RiwayatNewController::class, 'index']);
        Route::get('peminjam/normal/riwayat-new/{id}', [\App\Http\Controllers\Peminjam\RiwayatNewController::class, 'show']);

        Route::resource('peminjam/estafet/peminjaman', \App\Http\Controllers\Peminjam\EstafetPeminjamanController::class);
        Route::resource('peminjam/estafet/pengembalian', \App\Http\Controllers\Peminjam\EstafetPengembalianController::class);
        Route::resource('peminjam/estafet/riwayat', \App\Http\Controllers\Peminjam\EstafetRiwayatController::class);

        Route::resource('peminjam/tagihan', \App\Http\Controllers\Peminjam\TagihanController::class);

        Route::get('peminjam/tatacara', [\App\Http\Controllers\Peminjam\TatacaraController::class, 'index']);

        Route::get('peminjam/kuesioner', [\App\Http\Controllers\Peminjam\KuesionerController::class, 'index']);

        Route::get('peminjam/suratbebas', [\App\Http\Controllers\Peminjam\SuratbebasController::class, 'index']);
        Route::get('peminjam/suratbebas/cetak', [\App\Http\Controllers\Peminjam\SuratbebasController::class, 'cetak']);
    });

    Route::middleware('web')->group(function () {
        Route::get('web', [\App\Http\Controllers\Web\DashboardController::class, 'index']);
        Route::resource('web/berita', \App\Http\Controllers\Web\BeritaController::class);
    });
});
