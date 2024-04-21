<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['pasien'] = \App\Models\Pasien::latest()->paginate(10);
        return view('pasien_index', $data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pasien_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'no_pasien' => 'required|unique:pasiens,no_pasien',
            'nama' => 'required',
            'umur' => 'required|numeric',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'nullable',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:5000',
        ]);
        $model = new \App\Models\Pasien(); //membuat objek kosong
        $model->fill($requestData); //mengisi objek dengan data yang sudah divalidasi requestData
        $model->foto = $request->file('foto')->store('public'); //mengisi objek dengan pathFoto
        $model->save();
        flash('Data sudah disimpan')->success();
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['pasien'] = \App\Models\Pasien::findOrFail($id);
        return view('pasien_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requestData = $request->validate([
            'no_pasien' => 'required|unique:pasiens,no_pasien,' . $id,
            'nama' => 'required',
            'umur' => 'required|numeric',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5000', //foto boleh null
            'alamat' => 'nullable',
        ]);
        $model = \App\Models\Pasien::findOrFail($id);
        $model->fill($requestData);
        //karena di validasi foto boleh null, maka perlu pengecekan apakah ada file foto yang diupload
        //jika ada maka file foto lama dihapus dan diganti dengan file foto yang baru
        if ($request->hasFile('foto')) {
            Storage::delete($model->foto);
            $model->foto = $request->file('foto')->store('public');
        }
        $model->save();
        flash('Data sudah diupdate')->success();
        return redirect('/pasien');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = \App\Models\Pasien::findOrFail($id);
        if ($model->foto != null && Storage::exists($model->foto)) {
            Storage::delete($model->foto);
        }
        $model->delete();
        flash('Data sudah dihapus')->success();
        return back();
    }
}
