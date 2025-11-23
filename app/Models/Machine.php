<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'user_id', 'machine_name', 'auth_code', 'mac_id', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    

    // 🔹 Drain Relation
    public function drain()
    {
        return $this->hasOne(MachineDrain::class, 'machine_id');
    }

    // 🔹 Rodi Relation
    public function rodi()
    {
        return $this->hasOne(MachineRodi::class, 'machine_id');
    }

    // 🔹 Other Settings Relation
    public function other()
    {
        return $this->hasOne(MachineOther::class, 'machine_id');
    }
}

