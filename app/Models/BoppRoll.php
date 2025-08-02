<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoppRoll extends Model
{
    use HasFactory;

    protected $table = 'bopp_rolls';

    public function bopp(){
        return $this->belongsTo(Bopp::class, 'bopp_id');
    }

    public static function sumUnique($itemCode)
    {
        return self::where('item_code', $itemCode)->sum('weight');
    }


}
