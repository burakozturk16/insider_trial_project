<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    public $timestamps = false;
    protected $primaryKey = "row_id";

    public function getWeeks(int $week = 0){
        $data = $week == 0 ? Week::all() : Week::where("number", "=", $week)->get();
        $attrs = [];


        foreach ($data as $value) {
            $weekData = $value;
            $weekData['home'] = Team::select(['title', 'logo'])->find($value->home_id);
            $weekData['away'] = Team::select(['title', 'logo'])->find($value->away_id);

            if($week == 0){
                $attrs[$value->number][] = $weekData;
            }else{
                $attrs[] = $weekData;
            }
        }

        return $attrs;
    }
}
