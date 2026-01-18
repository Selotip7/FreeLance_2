<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Http\Requests\PengaduanRequest;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;
class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengaduanRequest $request)
    {
        $pengaduans=$request->validated();

        //ambil file gambar dari request yang memiliki key 'gambar' 
        $foto=$request->file('gambar');

        // memberikan nama unique dan ditambah ekstensi default gamar request
        $fotoName=Str::uuid().".".$foto->getClientOriginalExtension();
        
        //membuat folder untuk menyimpan foto
        Storage::disk('public')->putFileAs('Gambar Aduan',$foto,$fotoName);

        $newPengaduans=$request->all();
        $newPengaduans['gambar']='Gambar Aduan/'.$fotoName;


        return response()->json([
            'gambar'=> asset('storage/'.$newPengaduans['gambar'])
        ]);

    }

    /**
     * Display the specified resource.
     */


    public function show(string $id)
    {
        //
    }

    public function totalPengaduan()
    {
        $total = Pengaduan::count(); // hitung semua baris
        return response()->json([
            'total_pengaduan' => $total
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
