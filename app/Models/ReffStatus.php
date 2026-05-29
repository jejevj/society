<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReffStatus extends Model
{
    use HasFactory;

    protected $table = 'reff_status';    
    protected $primaryKey = 'id_status'; 
    public $incrementing = true;       
    protected $keyType = 'int';        

    protected $fillable = [
        'kode_status',
        'keterangan_status',
        'deskripsi_status',
        'urutan_status',
    ];
}
