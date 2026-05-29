<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReffMenu extends Model
{
    use HasFactory;

    protected $table = 'reff_menu';    
    protected $primaryKey = 'id_menu'; 
    public $incrementing = true;       
    protected $keyType = 'int';        

    protected $fillable = [
        'nama_menu',
        'jenis_menu',
        'kode_menu',
        'icon_menu',
        'parent_menu',
        'urutan_menu',
        'deskripsi_menu',
    ];
}
