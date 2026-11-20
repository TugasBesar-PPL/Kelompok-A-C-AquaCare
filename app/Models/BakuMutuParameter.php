<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BakuMutuParameter extends Model
{
    protected $table = 'baku_mutu_parameter';
    protected $primaryKey = 'parameter_id';

    protected $fillable = [
        'kode_parameter', 'nama_parameter', 'satuan',
        'nilai_minimum', 'nilai_maksimum',
        'batas_waspada_min', 'batas_waspada_maks',
        'tanggal_berlaku', 'diubah_oleh'
    ];
}