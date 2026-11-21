<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Mendukung input 'tipe' maupun 'kategori'
        if ($this->has('tipe') && !$this->has('kategori')) {
            $this->merge(['kategori' => $this->input('tipe')]);
        }
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'kategori' => ['required', 'in:pengumuman,edukasi'],
            'status_publikasi' => ['nullable', 'in:draft,published'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul konten wajib diisi.',
            'judul.string' => 'Judul konten harus berupa teks.',
            'judul.max' => 'Judul konten maksimal 255 karakter.',
            'isi.required' => 'Isi materi konten wajib diisi.',
            'isi.string' => 'Isi materi harus berupa teks.',
            'kategori.required' => 'Tipe konten wajib dipilih (pengumuman atau edukasi).',
            'kategori.in' => 'Tipe konten hanya boleh berupa pengumuman atau edukasi.',
            'status_publikasi.in' => 'Status publikasi harus berupa draft atau published.',
            'gambar.image' => 'Berkas lampiran harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus berupa JPG atau PNG.',
            'gambar.max' => 'Ukuran gambar maksimal 5 MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->is('api/*')) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validasi pembaruan pengumuman/edukasi gagal.',
                'errors' => $validator->errors()
            ], 422));
        }

        parent::failedValidation($validator);
    }
}

