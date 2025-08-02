<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    // protected $table = 'permission_category';
    protected $table = 'permissions';

    protected $fillable = ['name', 'slug', 'route', 'parent_id', 'feature'];
    
    protected $guard_name = 'web';
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_has_permissions');
    // }

    public function mainmodule(){
        return $this->belongsTo(Module::class, 'main_module');
    }

    public function submodule(){
        return $this->belongsTo(Module::class, 'sub_module');
    }
}
