<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCylinder extends Model
{
    use HasFactory;
    protected $table = 'job_cylinder';
    protected $fillable = ['job_detail_id', 'job_circum', 'job_pet', 'shell_circum', 'shell_pet', 'cylinder_repeat', 'cylinder_count'];
}
