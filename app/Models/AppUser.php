<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
     use HasFactory;

    protected $table = 'app_user';    
    protected $primaryKey = 'id_user'; 
    public $incrementing = true;       
    protected $keyType = 'int';        

    protected $fillable = [
        'role_id',
        'organisasi_id',
        'nama_user',
        'username_user',
        'password_user',
        'foto_user',
        'status_user',
    ];
}
