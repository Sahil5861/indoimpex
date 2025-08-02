<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFabric extends Model
{
    use HasFactory;

    protected $table = 'job_fabric';
    protected $fillable = ['job_detail_id', 'fabric_item_code', 'job_fabric_size', 'job_fabric_type', 'job_fabric_gsm', 'job_fabric_weight'];
}
