<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use SoftDeletes;
    protected $table = 'pasien4';
    protected $primaryKey = 'id_pasien';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uptd', 
        'tanggal',
        'no_index',
        'nama',
        'alamat',
        'kota',
        'kecamatan',
        'kelurahan',
        'nik',
        'umur',
        'tanggal_lahir',
        'jenis_kelamin',
        'pendidikan',
        'pekerjaan',
        'tipe_pasien',
        'keluhan',
    ];

    
}
