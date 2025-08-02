<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonWovenItem extends Model
{
    use HasFactory;

    protected $table = 'master_non';

    protected $fillable = [
        'item_code',
        'non_size',
        'non_color',
        'non_gsm',
    ];

    public function category()
    {
        return $this->belongsTo(NonWovenCategory::class, 'non_color', 'category_value');
    }
}
