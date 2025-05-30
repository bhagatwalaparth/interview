<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prize extends Model
{

    protected $guarded = ['id'];

    public static function nextPrize()
    {
        // TODO: Implement nextPrize() logic here.
        $prizes = self::all();
        $probabilities = $prizes->pluck('id')->toArray();
        $selectedPrizeIndex = array_rand($probabilities);
        $selectedPrize = $prizes[$selectedPrizeIndex];
        $selectedPrize->increment('awarded');
    }
}
