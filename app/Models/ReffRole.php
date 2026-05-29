<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReffRole extends Model
{
    use HasFactory;

    protected $table = 'reff_role';    
    protected $primaryKey = 'id_role'; 
    public $incrementing = true;       
    protected $keyType = 'int';        

    protected $fillable = [
        'nama_role',
        'kode_role',
        'deskripsi_role',
    ];
}
