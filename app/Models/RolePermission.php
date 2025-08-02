<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;
    protected $table = 'role_permissions';

    // protected $fillable = ['can_view', 'can_edit', 'can_add', 'can_delete'];
    protected $fillable = ['role_id', 'permission_id'];
}
