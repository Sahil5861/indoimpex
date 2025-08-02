<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use softDeletes;

    

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
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'OTP',
        'created_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    protected $table = "users";
}
