<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey_vito extends Model
{
    protected $table = 'survey_vito';

    protected $primaryKey = '_id';


    protected $fillable = [
        'nama',
        'saran',
        'jenis_kelamin',
    ];
}
