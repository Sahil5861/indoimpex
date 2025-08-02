<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobHandle extends Model
{
    use HasFactory;

    protected $table = 'job_handle';
    protected $fillable = ['job_detail_id', 'job_handle_color', 'job_handle_gsm', 'job_handle_weight'];

}
