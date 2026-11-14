<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakuMutuParameter extends Model
{
    protected $table = 'baku_mutu_parameter';

    protected $fillable = [
        'nama_parameter',
        'satuan',
        'nilai_minimum',
        'nilai_maksimum',
    ];

    protected function casts(): array
    {
        return [
            'nilai_minimum' => 'decimal:2',
            'nilai_maksimum' => 'decimal:2',
        ];
    }
}
