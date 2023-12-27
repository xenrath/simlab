<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaranController;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize:clear');
    return '<h1>Reoptimized class loader</h1>';
});

Auth::routes();

Route::get('logout', function () {
    Auth::logout();
    return redirect()->to('/');
});

Route::get('absen', [AbsenController::class, 'index']);
Route::post('absen', [AbsenController::class, 'store']);
Route::get('absen/scan', [AbsenController::class, 'scan']);
Route::post('absen/scan', [AbsenController::class, 'scan_proses']);

Route::middleware('auth')->group(function () {

    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile/update', [ProfileController::class, 'update']);

    Route::resource('saran', SaranController::class);

    Route::middleware('dev')->group(function () {
        Route::get('dev', [\App\Http\Controllers\Dev\DashboardController::class, 'index']);

        Route::get('dev/peminjaman/hapus_draft', [\App\Http\Controllers\Dev\PeminjamanController::class, 'hapus_draft']);
        Route::resource('dev/peminjaman', \App\Http\Controllers\Dev\PeminjamanController::class)->only(['index', 'show']);

        Route::get('dev/user/export', [\App\Http\Controllers\Dev\UserController::class, 'export']);
        Route::post('dev/user/import', [\App\Http\Controllers\Dev\UserController::class, 'import']);
        Route::get('dev/user/trash', [\App\Http\Controllers\Dev\UserController::class, 'trash']);
        Route::get('dev/user/restore/{id?}', [\App\Http\Controllers\Dev\UserController::class, 'restore']);
        Route::get('dev/user/delete/{id?}', [\App\Http\Controllers\Dev\UserController::class, 'delete']);
        Route::post('dev/user/aktivasi', [\App\Http\Controllers\Dev\UserController::class, 'aktivasi']);
        Route::get('dev/user/refresh-user', [\App\Http\Controllers\Dev\UserController::class, 'refresh_user']);
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

        Route::resource('dev/kuesioner', \App\Http\Controllers\Dev\KuesionerController::class);
        Route::resource('dev/pertanyaan-kuesioner', \App\Http\Controllers\Dev\PertanyaanKuesionerController::class);

        Route::resource('dev/praktik', \App\Http\Controllers\Dev\PraktikController::class);

        Route::resource('dev/satuan', \App\Http\Controllers\Dev\SatuanController::class);
    });

    Route::middleware('kalab')->group(function () {
        Route::get('kalab', [\App\Http\Controllers\Kalab\DashboardController::class, 'index']);

        Route::resource('kalab/admin', \App\Http\Controllers\Kalab\AdminController::class)->only('index', 'show');
        Route::resource('kalab/laboran', \App\Http\Controllers\Kalab\LaboranController::class)->only('index', 'show');
        Route::resource('kalab/peminjam', \App\Http\Controllers\Kalab\PeminjamController::class)->only('index', 'show');
        Route::resource('kalab/tamu', \App\Http\Controllers\Kalab\TamuController::class)->only('index', 'show');
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

        Route::post('kalab/kuesioner/pertanyaan', [\App\Http\Controllers\Kalab\KuesionerController::class, 'pertanyaan']);
        Route::get('kalab/kuesioner/download/{id}/{tahun}', [\App\Http\Controllers\Kalab\KuesionerController::class, 'download']);
        Route::get('kalab/kuesioner/grafik/{id}', [\App\Http\Controllers\Kalab\KuesionerController::class, 'grafik']);
        Route::resource('kalab/kuesioner', \App\Http\Controllers\Kalab\KuesionerController::class);
        // Route::resource('kalab/pertanyaan-kuesioner', \App\Http\Controllers\Kalab\PertanyaanKuesionerController::class);
        Route::get('kalab/absen', [\App\Http\Controllers\Kalab\AbsenController::class, 'index']);

        // Route::resource('kalab/berita', \App\Http\Controllers\Kalab\BeritaController::class);

        Route::resource('kalab/arsip', \App\Http\Controllers\Kalab\ArsipController::class);
    });

    Route::middleware('admin')->group(function () {
        Route::get('admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

        Route::get('admin/peminjaman', [\App\Http\Controllers\Admin\Peminjaman\DashboardController::class, 'index']);
        Route::get('admin/peminjaman/search_items', [\App\Http\Controllers\Admin\Peminjaman\DashboardController::class, 'search_items']);
        Route::get('admin/peminjaman/add_item/{id}', [\App\Http\Controllers\Admin\Peminjaman\DashboardController::class, 'add_item']);

        Route::get('admin/peminjaman/create', [\App\Http\Controllers\Admin\Peminjaman\CreateController::class, 'create']);
        Route::post('admin/peminjaman/create/store', [\App\Http\Controllers\Admin\Peminjaman\CreateController::class, 'store']);

        Route::get('admin/peminjaman/proses', [\App\Http\Controllers\Admin\Peminjaman\ProsesController::class, 'index']);
        Route::get('admin/peminjaman/proses/show/{id}', [\App\Http\Controllers\Admin\Peminjaman\ProsesController::class, 'show']);
        Route::get('admin/peminjaman/proses/konfirmasi/{id}', [\App\Http\Controllers\Admin\Peminjaman\ProsesController::class, 'konfirmasi']);
        Route::post('admin/peminjaman/proses/konfirmasi_selesai/{id}', [\App\Http\Controllers\Admin\Peminjaman\ProsesController::class, 'konfirmasi_selesai']);

        Route::get('admin/peminjaman/selesai', [\App\Http\Controllers\Admin\Peminjaman\SelesaiController::class, 'index']);
        Route::get('admin/peminjaman/selesai/{id}', [\App\Http\Controllers\Admin\Peminjaman\SelesaiController::class, 'show']);

        Route::get('admin/peminjaman/tagihan', [\App\Http\Controllers\Admin\Peminjaman\TagihanController::class, 'index']);
        Route::get('admin/peminjaman/tagihan/{id}', [\App\Http\Controllers\Admin\Peminjaman\TagihanController::class, 'show']);
        Route::post('admin/peminjaman/tagihan/konfirmasi/{id}', [\App\Http\Controllers\Admin\Peminjaman\TagihanController::class, 'konfirmasi']);
        Route::get('admin/peminjaman/tagihan/hubungi/{id}', [\App\Http\Controllers\Admin\Peminjaman\TagihanController::class, 'hubungi']);

        Route::get('admin/peminjaman/get_items', [\App\Http\Controllers\Admin\PeminjamanController::class, 'get_items']);
        Route::get('admin/peminjaman/hubungi/{id}', [\App\Http\Controllers\Admin\PeminjamanController::class, 'hubungi']);
        // Route::resource('admin/peminjaman', \App\Http\Controllers\Admin\PeminjamanController::class);

        Route::get('admin/user/export', [\App\Http\Controllers\Admin\UserController::class, 'export']);
        Route::post('admin/user/import', [\App\Http\Controllers\Admin\UserController::class, 'import']);
        Route::get('admin/user/reset-password/{id}', [\App\Http\Controllers\Admin\UserController::class, 'reset_password']);
        Route::resource('admin/user', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('admin/kalab', \App\Http\Controllers\Admin\KalabController::class);

        Route::get('admin/peminjam/export', [\App\Http\Controllers\Admin\PeminjamController::class, 'export']);
        Route::post('admin/peminjam/import', [\App\Http\Controllers\Admin\PeminjamController::class, 'import']);
        Route::resource('admin/peminjam', \App\Http\Controllers\Admin\PeminjamController::class);
        Route::get('admin/exportpeminjam', [\App\Http\Controllers\Admin\PeminjamController::class, 'exportpeminjam']);

        Route::get('admin/pengguna/mahasiswa/export', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'export']);
        Route::post('admin/pengguna/mahasiswa/import', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'import']);
        Route::post('admin/pengguna/mahasiswa/import', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'import']);
        Route::get('admin/pengguna/mahasiswa/ubah_tingkat', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'ubah_tingkat']);
        Route::post('admin/pengguna/mahasiswa/ubah_tingkat_proses', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'ubah_tingkat_proses']);
        Route::resource('admin/pengguna/mahasiswa', \App\Http\Controllers\Admin\Pengguna\MahasiswaController::class);
        Route::get('admin/exportmahasiswa', [\App\Http\Controllers\Admin\Pengguna\MahasiswaController::class, 'exportpeminjam']);

        Route::get('admin/pengguna/laboran/reset_password/{id}', [\App\Http\Controllers\Admin\Pengguna\LaboranController::class, 'reset_password']);
        Route::resource('admin/pengguna/laboran', \App\Http\Controllers\Admin\Pengguna\LaboranController::class);

        Route::resource('admin/pengguna/tamu', \App\Http\Controllers\Admin\Pengguna\TamuController::class);

        Route::resource('admin/ruang', \App\Http\Controllers\Admin\RuangController::class);

        Route::get('admin/barang-normal', [\App\Http\Controllers\Admin\BarangController::class, 'normal']);
        Route::get('admin/barang-rusak', [\App\Http\Controllers\Admin\BarangController::class, 'rusak']);
        Route::get('admin/barang/export', [\App\Http\Controllers\Admin\BarangController::class, 'export']);
        Route::post('admin/barang/import', [\App\Http\Controllers\Admin\BarangController::class, 'import']);
        Route::post('admin/barang/import-kode', [\App\Http\Controllers\Admin\BarangController::class, 'import_kode']);
        Route::resource('admin/barang', \App\Http\Controllers\Admin\BarangController::class);

        Route::get('admin/bahan/export', [\App\Http\Controllers\Admin\BahanController::class, 'export']);
        Route::post('admin/bahan/import', [\App\Http\Controllers\Admin\BahanController::class, 'import']);
        Route::resource('admin/bahan', \App\Http\Controllers\Admin\BahanController::class);

        Route::get('admin/stokbarang/export', [\App\Http\Controllers\Admin\StokBarangController::class, 'export']);
        Route::post('admin/stokbarang/import', [\App\Http\Controllers\Admin\StokBarangController::class, 'import']);
        Route::resource('admin/stokbarang', \App\Http\Controllers\Admin\StokBarangController::class);

        Route::get('admin/stokbahan/satuan/{id}', [\App\Http\Controllers\Admin\StokBahanController::class, 'satuan']);
        Route::resource('admin/stokbahan', \App\Http\Controllers\Admin\StokBahanController::class);

        Route::get('admin/pengambilan/ruang/{id}', [\App\Http\Controllers\Admin\PengambilanController::class, 'ruang']);
        Route::get('admin/pengambilan/pilih', [\App\Http\Controllers\Admin\PengambilanController::class, 'pilih']);
        Route::resource('admin/pengambilan', \App\Http\Controllers\Admin\PengambilanController::class);
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

        Route::get('laboran/peminjaman/{id}/tolak', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'tolak']);
        Route::get('laboran/peminjaman/{id}/setujui', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'setujui']);

        Route::get('laboran/peminjaman/show_mandiri/{id}', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'show_mandiri']);
        Route::get('laboran/peminjaman/show_estafet/{id}', [\App\Http\Controllers\Laboran\PeminjamanController::class, 'show_estafet']);
        Route::resource('laboran/peminjaman', \App\Http\Controllers\Laboran\PeminjamanController::class);

        Route::get('laboran/peminjaman-new/{id}/setujui', [\App\Http\Controllers\Laboran\PeminjamanNewController::class, 'setujui']);

        Route::resource('laboran/peminjaman-new/praktik-laboratorium', \App\Http\Controllers\Laboran\Peminjaman\LaboratoriumController::class);
        Route::resource('laboran/peminjaman-new/praktik-kelas', \App\Http\Controllers\Laboran\Peminjaman\KelasController::class);
        Route::resource('laboran/peminjaman-new/praktik-luar', \App\Http\Controllers\Laboran\Peminjaman\LuarController::class);
        Route::resource('laboran/peminjaman-new', \App\Http\Controllers\Laboran\PeminjamanNewController::class);
        // Route::get('laboran/pilih/{id}', [\App\Http\Controllers\LaboranController::class, 'peminjaman_detail']);

        Route::resource('laboran/tagihan/praktik-laboratorium', \App\Http\Controllers\Laboran\Tagihan\LaboratoriumController::class);
        Route::resource('laboran/tagihan/praktik-kelas', \App\Http\Controllers\Laboran\Tagihan\KelasController::class);
        Route::resource('laboran/tagihan/praktik-luar', \App\Http\Controllers\Laboran\Tagihan\LuarController::class);
        Route::get('laboran/tagihan', [\App\Http\Controllers\Laboran\TagihanController::class, 'index']);
        Route::get('laboran/tagihan/{id}', [\App\Http\Controllers\Laboran\TagihanController::class, 'show']);
        Route::post('laboran/tagihan/konfirmasi/{id}', [\App\Http\Controllers\Laboran\TagihanController::class, 'konfirmasi']);
        Route::get('laboran/tagihan/hubungi/{id}', [\App\Http\Controllers\Laboran\TagihanController::class, 'hubungi']);

        Route::get('laboran/laporan', [\App\Http\Controllers\Laboran\LaporanController::class, 'index']);
        Route::get('laboran/laporan/print', [\App\Http\Controllers\Laboran\LaporanController::class, 'print']);
        Route::get('laboran/laporan/{id}', [\App\Http\Controllers\Laboran\LaporanController::class, 'show']);

        // Lab Terpadu

        Route::get('laboran/pengembalian', [\App\Http\Controllers\Laboran\PengembalianController::class, 'index']);
        Route::get('laboran/pengembalian/{id}', [\App\Http\Controllers\Laboran\PengembalianController::class, 'show']);
        Route::get('laboran/pengembalian/{id}/konfirmasi', [\App\Http\Controllers\Laboran\PengembalianController::class, 'konfirmasi']);
        Route::post('laboran/pengembalian/{id}/p_konfirmasi', [\App\Http\Controllers\Laboran\PengembalianController::class, 'p_konfirmasi']);
        Route::post('laboran/pengembalian/{id}/update', [\App\Http\Controllers\Laboran\PengembalianController::class, 'update']);
        Route::get('laboran/pengembalian/{id}/cetak', [\App\Http\Controllers\Laboran\PengembalianController::class, 'cetak']);
        Route::get('laboran/pengembalian/hubungi/{id}', [\App\Http\Controllers\Laboran\PengembalianController::class, 'hubungi']);

        Route::get('laboran/pengembalian-new/{id}/konfirmasi', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'konfirmasi']);
        Route::post('laboran/pengembalian-new/{id}/p_konfirmasi', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'p_konfirmasi']);
        Route::post('laboran/pengembalian-new/{id}/update', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'update']);
        Route::get('laboran/pengembalian-new/{id}/cetak', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'cetak']);
        Route::get('laboran/pengembalian-new/{id}/hubungi', [\App\Http\Controllers\Laboran\PengembalianNewController::class, 'hubungi']);
        Route::resource('laboran/pengembalian-new', \App\Http\Controllers\Laboran\PengembalianNewController::class);

        Route::resource('laboran/riwayat', \App\Http\Controllers\Laboran\RiwayatController::class);

        Route::resource('laboran/riwayat-new/praktik-laboratorium', \App\Http\Controllers\Laboran\Riwayat\LaboratoriumController::class);
        Route::resource('laboran/riwayat-new/praktik-kelas', \App\Http\Controllers\Laboran\Riwayat\KelasController::class);
        Route::resource('laboran/riwayat-new/praktik-luar', \App\Http\Controllers\Laboran\Riwayat\LuarController::class);
        Route::resource('laboran/riwayat-new', \App\Http\Controllers\Laboran\RiwayatNewController::class);

        // Farmasi

        Route::get('laboran/kelompok/peminjaman/konfirmasi_setuju/{id}', [\App\Http\Controllers\Laboran\KelompokPeminjamanController::class, 'konfirmasi_setuju']);
        Route::resource('laboran/kelompok/peminjaman', \App\Http\Controllers\Laboran\KelompokPeminjamanController::class);

        Route::post('laboran/kelompok/pengembalian/konfirmasi_pengembalian/{id}', [\App\Http\Controllers\Laboran\KelompokPengembalianController::class, 'konfirmasi_pengembalian']);
        Route::resource('laboran/kelompok/pengembalian', \App\Http\Controllers\Laboran\KelompokPengembalianController::class);

        Route::resource('laboran/kelompok/riwayat', \App\Http\Controllers\Laboran\KelompokRiwayatController::class);
    });

    Route::middleware('peminjam')->group(function () {
        Route::middleware('bidan')->group(function () {
            Route::get('peminjam/bidan', [\App\Http\Controllers\Peminjam\Bidan\HomeController::class, 'index']);

            Route::post('peminjam/bidan/buat/create', [\App\Http\Controllers\Peminjam\Bidan\BuatController::class, 'create']);
            Route::resource('peminjam/bidan/buat', \App\Http\Controllers\Peminjam\Bidan\BuatController::class)->except('create');

            Route::resource('peminjam/bidan/menunggu', \App\Http\Controllers\Peminjam\Bidan\MenungguController::class)->except('create', 'store');

            Route::resource('peminjam/bidan/proses', \App\Http\Controllers\Peminjam\Bidan\ProsesController::class)->except('create', 'store', 'destroy');

            Route::resource('peminjam/bidan/riwayat', \App\Http\Controllers\Peminjam\Bidan\RiwayatController::class)->only('index', 'show');

            Route::resource('peminjam/bidan/tagihan', \App\Http\Controllers\Peminjam\Bidan\TagihanController::class)->only('index', 'show');
        });
        Route::middleware('perawat')->group(function () {
            Route::get('peminjam/perawat', [\App\Http\Controllers\Peminjam\Perawat\HomeController::class, 'index']);

            Route::post('peminjam/perawat/buat/create', [\App\Http\Controllers\Peminjam\Perawat\BuatController::class, 'create']);
            Route::resource('peminjam/perawat/buat', \App\Http\Controllers\Peminjam\Perawat\BuatController::class)->except('create');

            Route::resource('peminjam/perawat/menunggu', \App\Http\Controllers\Peminjam\Perawat\MenungguController::class)->except('create', 'store');

            Route::resource('peminjam/perawat/proses', \App\Http\Controllers\Peminjam\Perawat\ProsesController::class)->except('create', 'store', 'destroy');

            Route::resource('peminjam/perawat/riwayat', \App\Http\Controllers\Peminjam\Perawat\RiwayatController::class)->only('index', 'show');

            Route::resource('peminjam/perawat/tagihan', \App\Http\Controllers\Peminjam\Perawat\TagihanController::class)->only('index', 'show');
        });
        Route::middleware('k3')->group(function () {
            Route::get('peminjam/k3', [\App\Http\Controllers\Peminjam\K3\HomeController::class, 'index']);

            Route::post('peminjam/k3/buat/create', [\App\Http\Controllers\Peminjam\K3\BuatController::class, 'create']);
            Route::resource('peminjam/k3/buat', \App\Http\Controllers\Peminjam\K3\BuatController::class)->except('create');

            Route::resource('peminjam/k3/menunggu', \App\Http\Controllers\Peminjam\K3\MenungguController::class)->except('create', 'store');

            Route::resource('peminjam/k3/proses', \App\Http\Controllers\Peminjam\K3\ProsesController::class)->except('create', 'store', 'destroy');

            Route::resource('peminjam/k3/riwayat', \App\Http\Controllers\Peminjam\K3\RiwayatController::class)->only('index', 'show');

            Route::resource('peminjam/k3/tagihan', \App\Http\Controllers\Peminjam\K3\TagihanController::class)->only('index', 'show');
        });
        Route::get('peminjam', [\App\Http\Controllers\Peminjam\DashboardController::class, 'index']);
        Route::get('peminjam/check', [\App\Http\Controllers\Peminjam\DashboardController::class, 'check']);
        Route::get('peminjam/pinjam', [\App\Http\Controllers\Peminjam\DashboardController::class, 'pinjam']);
        Route::get('peminjam/pilih', [\App\Http\Controllers\Peminjam\DashboardController::class, 'pilih']);
        Route::post('peminjam/pinjam/proses', [\App\Http\Controllers\Peminjam\DashboardController::class, 'proses']);
        Route::get('peminjam/peminjaman/search_items', [\App\Http\Controllers\Peminjam\DashboardController::class, 'search_items']);
        Route::get('peminjam/search_farm', [\App\Http\Controllers\Peminjam\PeminjamanController::class, 'search']);
        Route::get('peminjam/peminjaman/add_item/{id}', [\App\Http\Controllers\Peminjam\DashboardController::class, 'add_item']);
        Route::get('peminjam/peminjaman/get_estafet/{id}', [\App\Http\Controllers\Peminjam\DashboardController::class, 'get_estafet']);
        Route::get('peminjam/peminjaman/delete_item/{id}', [\App\Http\Controllers\Peminjam\DashboardController::class, 'delete_item']);
        Route::get('peminjam/peminjaman/search_anggotas', [\App\Http\Controllers\Peminjam\DashboardController::class, 'search_anggotas']);
        Route::get('peminjam/peminjaman/add_anggota/{id}', [\App\Http\Controllers\Peminjam\DashboardController::class, 'add_anggota']);

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

        Route::get('peminjam/kuesioner', [\App\Http\Controllers\Peminjam\KuesionerController::class, 'index']);
        Route::get('peminjam/kuesioner/{id}', [\App\Http\Controllers\Peminjam\KuesionerController::class, 'create']);
        Route::post('peminjam/kuesioner/{id}', [\App\Http\Controllers\Peminjam\KuesionerController::class, 'store']);

        Route::get('peminjam/suratbebas', [\App\Http\Controllers\Peminjam\SuratbebasController::class, 'index']);
        Route::get('peminjam/suratbebas/cetak', [\App\Http\Controllers\Peminjam\SuratbebasController::class, 'cetak']);

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
});
