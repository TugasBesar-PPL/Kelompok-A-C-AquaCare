<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakuMutuParameter extends Model
{
    protected $table = 'baku_mutu_parameter';

    protected $fillable = [
        'kode_parameter',
        'nama_parameter',
        'satuan',
        'nilai_minimum',
        'nilai_maksimum',
        'batas_waspada_min',
        'batas_waspada_maks',
        'tanggal_berlaku',
        'diubah_oleh',
    ];

    protected function casts(): array
    {
        return [
            'nilai_minimum'      => 'decimal:2',
            'nilai_maksimum'     => 'decimal:2',
            'batas_waspada_min'  => 'decimal:2',
            'batas_waspada_maks' => 'decimal:2',
            // 'date' mengembalikan Carbon; gunakan ->toDateString() untuk format Y-m-d
            'tanggal_berlaku'    => 'date',
        ];
    }
}
