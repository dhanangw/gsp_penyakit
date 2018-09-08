<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RentangKategori extends Model
{
    use SoftDeletes;
    protected $table = 'rentang_kategori';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kategori_id',
        'name',
        'batas_bawah',
        'batas_atas',
        'value',
    ];

    
}
