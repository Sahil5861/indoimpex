<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFlexo extends Model
{
    use HasFactory;

    protected $table = 'job_flexo_bopp';
    protected $fillable = ['job_id', 'flexo_circum', 'flexo_pet', 'box_circum', 'box_pet', 'box_count'];

}
