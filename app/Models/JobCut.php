<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCut extends Model
{
    use HasFactory;
    protected $table = 'job_cut_wastage';
    protected $fillable = ['job_detail_id', 'cut_type', 'cut_wastage'];


}
