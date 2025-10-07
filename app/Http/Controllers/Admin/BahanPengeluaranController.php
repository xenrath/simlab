<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Prodi;
use App\Models\RekapBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BahanPengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $prodi = $request->get('prodi');

        $rekap_bahans = RekapBahan::where('status', 'keluar')
            ->select(
                'id',
                'bahan_nama',
                'prodi_id',
                'prodi_nama',
                'jumlah',
                'satuan',
                'created_at',
            )
            ->when($prodi, function ($query) use ($prodi) {
                $query->where('prodi_nama', $prodi);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('bahan_nama', 'like', "%$keyword%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

        $prodis = Prodi::select('nama')->where('is_prodi', true)->get();

        return view('admin.bahan-pengeluaran.index', compact('rekap_bahans', 'prodis'));
    }

    public function create()
    {
        $metode = request()->input('metode');

        switch ($metode) {
            case 'manual':
                return redirect('admin/bahan-pengeluaran/manual');
            case 'scan':
                return redirect('admin/bahan-pengeluaran/scan');
            default:
                return back()->with('error', 'Metode belum dipilih!');
        }
    }

    public function create_manual()
    {
        $bahans = Bahan::select('id', 'nama', 'prodi_id')
            ->with('prodi:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();
        $prodis = Prodi::select('id', 'nama')->where('is_prodi', true)->get();

        return view('admin.bahan-pengeluaran.create_manual', compact('bahans', 'prodis'));
    }

    public function create_scan()
    {
        return view('admin.bahan-pengeluaran.create_scan');
    }

    public function store_manual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bahans' => 'required|array',
            'bahans.*.jumlah' => 'required|numeric|gt:0',
        ], [
            'bahans.required' => 'Bahan belum ditambahkan!',
            'bahans.*.jumlah.required' => 'Jumlah belum diisi!',
            'bahans.*.numeric.required' => 'Jumlah harus numeric!',
            'bahans.*.gt.required' => 'Jumlah tidak boleh 0!',
        ]);

        $old_bahans = [];
        if ($request->bahans) {
            foreach ($request->bahans as $value) {
                $old_bahans[] = [
                    'id' => $value['bahan_id'],
                    'nama' => $value['bahan_nama'],
                    'prodi' => [
                        'id' => $value['prodi_id'],
                        'nama' => $value['prodi_nama']
                    ],
                    'jumlah' => (float) $value['jumlah'],
                    'satuan_pinjam' => $value['satuan_pinjam'],
                ];
            }
        }

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('old_bahans', $old_bahans)
                ->with('error', 'Gagal membuat Pengeluaran Bahan!');
        }

        $insert_data = [];
        foreach ($old_bahans as $value) {
            $insert_data[] = [
                'bahan_id'    => $value['id'],
                'bahan_nama'  => $value['nama'],
                'prodi_id'    => $value['prodi']['id'],
                'prodi_nama'  => $value['prodi']['nama'],
                'jumlah'      => $value['jumlah'],
                'satuan'      => $value['satuan_pinjam'],
                'status'      => 'keluar',
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        if (!empty($insert_data)) {
            RekapBahan::insert($insert_data);
        }

        return redirect('admin/bahan-pengeluaran')->with('success', 'Berhasil membuat Pengeluaran Bahan');
    }

    public function store_scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bahans' => 'required|array',
        ], [
            'bahans.required' => 'Bahan belum ditambahkan!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('error', 'Gagal membuat Pengeluaran Bahan!');
        }

        $ids = collect($request->bahans)->pluck('id');

        $bahans = Bahan::whereIn('id', $ids)
            ->select('id', 'nama', 'prodi_id', 'satuan_pinjam')
            ->with('prodi:id,nama')
            ->get()
            ->keyBy('id');

        $insert_data = [];
        foreach ($request->bahans as $value) {
            $bahan = $bahans->get($value['id']);
            if (!$bahan) continue;
            $insert_data[] = [
                'bahan_id'   => $bahan->id,
                'bahan_nama' => $bahan->nama,
                'prodi_id'   => $bahan->prodi_id,
                'prodi_nama' => $bahan->prodi->nama,
                'jumlah'     => (float) $value['jumlah'],
                'satuan'     => $bahan->satuan_pinjam,
                'status'     => 'keluar',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($insert_data)) {
            RekapBahan::insert($insert_data);
        }

        return redirect('admin/bahan-pengeluaran')
            ->with('success', 'Berhasil mengirim Pengeluaran');
    }

    public function destroy($id)
    {
        RekapBahan::where('id', $id)->delete();

        return back()->with('success', 'Berhasil menghapus Bahan');
    }
}
