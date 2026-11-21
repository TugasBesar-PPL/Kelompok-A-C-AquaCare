<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateWaterPointRequest extends FormRequest
{
    public function authorize(): bool
    {
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
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama titik air wajib diisi.',
            'nama.string' => 'Nama titik air harus berupa teks.',
            'nama.max' => 'Nama titik air maksimal 255 karakter.',
            'jenis.required' => 'Jenis titik air wajib diisi.',
            'jenis.string' => 'Jenis titik air harus berupa teks.',
            'jenis.max' => 'Jenis titik air maksimal 255 karakter.',
            'alamat.required' => 'Alamat titik air wajib diisi.',
            'alamat.string' => 'Alamat titik air harus berupa teks.',
            'alamat.max' => 'Alamat titik air maksimal 255 karakter.',
            'wilayah.required' => 'Wilayah wajib diisi.',
            'wilayah.string' => 'Wilayah harus berupa teks.',
            'wilayah.max' => 'Wilayah maksimal 255 karakter.',
            'latitude.required' => 'Koordinat latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka numerik.',
            'latitude.between' => 'Latitude harus berada di antara rentang -90 sampai 90 derajat.',
            'longitude.required' => 'Koordinat longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka numerik.',
            'longitude.between' => 'Longitude harus berada di antara rentang -180 sampai 180 derajat.',
            'foto.image' => 'Berkas foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 5 MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->is('api/*')) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validasi pembaruan titik air gagal.',
                'errors' => $validator->errors()
            ], 422));
        }

        parent::failedValidation($validator);
    }
}

