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
        $data=$request->validated();
        
        if($request->hasFile('gambar')){

            //ambil file gambar dari request yang memiliki key 'gambar' 
            $foto=$request->file('gambar');
    
            // memberikan nama unique dan ditambah ekstensi default gamar request
            $fotoName=Str::uuid().".".$foto->getClientOriginalExtension();

            // membuat folder untuk menyimpan foto
            Storage::disk('public')->putFileAs('Gambar Aduan',$foto,$fotoName);
    
            
            $data['gambar']='Gambar Aduan/'.$fotoName;
        }

        // Melakukan tambah id user sesuai id user yang sedang login/aktif
        $data['id_user']=$request->user()->id;

        // Simpan ke database
        $pengaduan = Pengaduan::create($data);
        return response()->json([
            'gambar'=> asset('storage/'.$data['gambar']),
            'pengaduan'=>$pengaduan
        ],201);

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


    public function dataPengaduan(){
       $counts=Pengaduan::select('status')->selectRaw('count(*) as total')->groupBy('status')->pluck('total','status');
        $total = Pengaduan::count();

        return response()->json([
            'total'=>$total    ,
            'diproses'=>$counts->get('Diproses',0),
            'selesai'=> $counts->get('Selesai', 0),
            'tolak'=> $counts->get('Tolak', 0),
        ]);

        }

     public function showAll(){
        $pengaduans=Pengaduan::select('id','judul_laporan','deskripsi','kategori','status','gambar','catatan','tgl_pengaduan')->get();

        return response()->json([
            'data'=>$pengaduans
        ]);
     }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $pengaduan = Pengaduan::findOrFail($id);

        $data=$request->validate([
            'status'=>'nullable|in:Diproses,Selesai,Tolak',
            'catatan' => 'nullable|string',
        ]);

        if(isset($data['status'])){
    $pengaduan->status=$data['status'];
        }

        if (isset($data['status'])) {
            $pengaduan->status = $data['status'];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
