<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoppIssue extends Model
{
    use HasFactory;

    protected $table = 'bopp_issue';

    public static function itemCutWastage($itemCode)
{
    return self::where('item_code', $itemCode)->sum('cut_wastage');
}

    public static function itemRollUsed($itemCode)
    {
        return self::where('item_code', $itemCode)->sum('roll_used'); // Make sure this column exists
    }

    public static function sumCutWastage($rollId)
    {
        return self::where('bopp_roll_id', $rollId)->sum('cut_wastage');
    }

    public static function sumRollUsed($rollId)
    {
        return self::where('bopp_roll_id', $rollId)->sum('roll_used');
    }
}
