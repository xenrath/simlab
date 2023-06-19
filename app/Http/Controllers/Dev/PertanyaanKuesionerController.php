<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\PertanyaanKuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PertanyaanKuesionerController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kuesioner_id' => 'required',
            'pertanyaan' => 'required',
        ], [
            'kuesioner_id.required' => 'Kueisoner tidak boleh kosong!',
            'pertanyaan.required' => 'Pertanyaan tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            alert()->error('Error', $error[0]);
            return back();
        }

        PertanyaanKuesioner::create($request->all());

        return back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kuesioner_id' => 'required',
            'pertanyaan' => 'required',
        ], [
            'kuesioner_id.required' => 'Kueisoner tidak boleh kosong!',
            'pertanyaan.required' => 'Pertanyaan tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            alert()->error('Error', $error[0]);
            return back();
        }

        PertanyaanKuesioner::where('id', $id)->update([
            'pertanyaan' => $request->pertanyaan
        ]);

        return back();
    }

    public function destroy($id)
    {
        PertanyaanKuesioner::where('id', $id)->delete();

        return back();
    }
}
