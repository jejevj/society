<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReffOrganisasi extends Model
{
    protected $table = 'reff_organisasi';

    protected $fillable = ['kode_organisasi', 'nama_organisasi', 'singkatan_organisasi', 'web_organisasi', 'foto_organisasi', 'tmp_foto_organisasi', 'created_at', 'updated_at'];
}
