<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    use HasFactory;
    

    protected $table = "modules";
    protected $fillable = ['name', 'route_url'];


    // Role.php
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }


}
