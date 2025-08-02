<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDetails extends Authenticatable
{
    use HasFactory, Notifiable;    

    protected $table = 'job_details';
    protected $fillable = [
        'job_type_id',
        'job_unique_code',
        'party_id',
        'job_name_id',
        'variant_id',
        'printing_type',
        'bag_type',
        'bag_total_weight',
        'bag_circum',
        'bag_pet',
        'bag_gazette',
        'is_metallized',
        'job_description',
        'job_status',
        'approval_status',
        'created_at',
        'submit_date',
    ];

    public function party(){
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function jobName(){
        return $this->belongsTo(JobNames::class, 'job_name_id');
    }

    public function jobType(){
        return $this->belongsTo(JobTypes::class, 'job_type_id');
    }
   
}
