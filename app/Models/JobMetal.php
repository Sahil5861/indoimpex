<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobMetal extends Model
{
    use HasFactory;
    protected $table = 'job_metalised';
    protected $fillable = ['metal_item_code', 'job_metal_size', 'job_metal_type', 'job_metal_micron', 'job_metal_weight'];
}
