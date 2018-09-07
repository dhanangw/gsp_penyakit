<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Dummy extends Model
{
    use SoftDeletes;
    protected $table = 'dummy';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
        'nama',
        'kelamin',
        'umur',
        'berat_badan',
        'tinggi_badan',
        'suhu',
        'gejala',
        'diagnosa',
    ];

    
}
