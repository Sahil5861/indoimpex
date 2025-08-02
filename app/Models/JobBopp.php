<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobBopp extends Model
{
    use HasFactory;
    protected $table = 'job_bopp';
    protected $fillable = ['job_detail_id', 'bopp_item_code', 'job_bopp_size', 'job_bopp_type', 'job_bopp_micron', 'job_bopp_weight'];
}
