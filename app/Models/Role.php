<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    use HasFactory;
    // use softDeletes;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected $table = "roles";
    protected $dates = ['deleted_at'];

    protected $fillable = ['name'];


    // Role.php
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }


}
