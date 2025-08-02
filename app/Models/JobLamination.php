<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLamination extends Model
{
    use HasFactory;
    protected $table = 'job_lamination';
    protected $fillable = ['job_detail_id', 'job_lamination_mix', 'job_lamination_size', 'job_lamination_gsm', 'job_lamination_weight'];
}
