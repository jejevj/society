<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAddonRegistrasi extends Model
{
    protected $table = 'event_addon_registrasi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_addon_reg',
        'kode_registrasi',
        'kode_addon',
        'status',
        'catatan',
    ];

    public function addon()
    {
        return $this->belongsTo(EventAddon::class, 'kode_addon', 'kode_addon');
    }

    public function registrasi()
    {
        return $this->belongsTo(EventRegistrasi::class, 'kode_registrasi', 'kode_registrasi');
    }
}
