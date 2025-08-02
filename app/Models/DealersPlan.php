<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealersPlan extends Model
{
    use HasFactory;
    protected $table = 'dealersplan';
    protected $fillable = [
        'dealer_id',
        'name',
        'description',
        'price',
        'special_price',
        'expiry_date',
    ];
}
