<?php

namespace App\Http\Requests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PengaduanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul_laporan'=>'required|max:255|string',
            'deskripsi'=>'required|string',
            'kategori'=>'required|in:Pencurian,Tindakan Kriminal,Bencana Alam,Kerusakan Fasilitas Umum',
            'gambar'=>'required|image|mimes:jpeg,png,jpg|max:2048',
            'tgl_pengaduan'=>'required|date',
            
        ];
    }

    public function messages():array{
        return [
            'kategori.in'=>'Kategori : Pencurian,Tindakan Kriminal,Bencana Alam,Kerusakan Fasilitas Umum'
        ];

    }

    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(response()->json([
    //         'status' => false,
    //         'message' => 'Validasi gagal',
    //         'errors' => $validator->errors()
    //     ], 422));
    // }
}
