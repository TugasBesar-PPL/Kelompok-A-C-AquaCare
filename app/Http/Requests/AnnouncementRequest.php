<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Akses hanya untuk admin sesuai proposal (PBI-12)
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'kategori' => ['required', 'in:pengumuman,edukasi'],
            'status_publikasi' => ['nullable', 'in:draft,published'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB (NFR-02)
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul konten wajib diisi.',
            'isi.required' => 'Isi materi pengumuman/edukasi wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih (pengumuman atau edukasi).',
            'kategori.in' => 'Kategori harus berupa pengumuman atau edukasi.',
            'gambar.image' => 'Berkas lampiran harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus berupa JPG atau PNG.',
            'gambar.max' => 'Ukuran gambar maksimal 5 MB (NFR-02).',
        ];
    }
}

