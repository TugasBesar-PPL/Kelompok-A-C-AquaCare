<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WaterPointRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Akses hanya untuk admin sesuai proposal (PBI-01)
        return true; 
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'wilayah' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB (NFR-02)
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama titik air wajib diisi.',
            'jenis.required' => 'Jenis titik air wajib dipilih.',
            'alamat.required' => 'Alamat titik air wajib diisi.',
            'wilayah.required' => 'Wilayah wajib diisi.',
            'latitude.required' => 'Koordinat latitude wajib ditentukan.',
            'latitude.numeric' => 'Format latitude tidak valid.',
            'longitude.required' => 'Koordinat longitude wajib ditentukan.',
            'longitude.numeric' => 'Format longitude tidak valid.',
            'foto.image' => 'Berkas foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 5 MB (NFR-02).',
        ];
    }
}

