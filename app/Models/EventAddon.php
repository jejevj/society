<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAddon extends Model
{
    protected $table = 'event_addon';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_addon',
        'kode_event',
        'nama_addon',
        'deskripsi_addon',
        'gambar_addon',
        'harga_addon',
        'status_addon',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'kode_event', 'kode_event');
    }

    public function registrations()
    {
        return $this->hasMany(EventAddonRegistrasi::class, 'kode_addon', 'kode_addon');
    }
}
