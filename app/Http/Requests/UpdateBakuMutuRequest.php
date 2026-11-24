<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateBakuMutuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // TODO: batasi ke admin setelah auth Fathir di-merge
    }

    public function rules(): array
    {
        return [
            'nilai_minimum'      => ['required', 'numeric'],
            'nilai_maksimum'     => ['required', 'numeric', 'gt:nilai_minimum'],
            'batas_waspada_min'  => ['required', 'numeric', 'lte:nilai_minimum'],
            'batas_waspada_maks' => ['required', 'numeric', 'gte:nilai_maksimum'],
            'tanggal_berlaku'    => [
                'required',
                'date',
                'after_or_equal:today',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $kode = $this->route('kode');
                    $sudahAda = DB::table('baku_mutu_parameter')
                        ->where('kode_parameter', $kode)
                        ->whereDate('tanggal_berlaku', $value)
                        ->exists();

                    if ($sudahAda) {
                        $fail('Sudah ada versi dengan tanggal berlaku ini untuk parameter tersebut.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required'                     => ':attribute wajib diisi.',
            '*.numeric'                      => ':attribute harus berupa angka.',
            'nilai_maksimum.gt'              => 'Nilai maksimum harus lebih besar dari nilai minimum.',
            'batas_waspada_min.lte'          => 'Batas waspada minimum tidak boleh lebih besar dari nilai minimum.',
            'batas_waspada_maks.gte'         => 'Batas waspada maksimum tidak boleh lebih kecil dari nilai maksimum.',
            'tanggal_berlaku.after_or_equal' => 'Tanggal berlaku tidak boleh di masa lalu.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nilai_minimum'      => 'nilai minimum',
            'nilai_maksimum'     => 'nilai maksimum',
            'batas_waspada_min'  => 'batas waspada minimum',
            'batas_waspada_maks' => 'batas waspada maksimum',
            'tanggal_berlaku'    => 'tanggal berlaku',
        ];
    }
}
