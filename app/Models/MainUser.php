<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainUser extends Authenticatable
{
    use HasFactory, Notifiable;
    use softDeletes;

    protected $table = 'main_admin';

    

    // User.php
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermissionTo($permission)
    {
        // Check if the role associated with this user has the permission
        // return $this->role->permissions()->where('name', $permission)->exists();
    }


    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',        
        'hashed_password',
        'type',                
    ];
}
