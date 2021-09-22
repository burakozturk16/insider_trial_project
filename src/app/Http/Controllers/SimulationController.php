<?php

namespace App\Http\Controllers;

use App\Team;
use App\Week;
use App\Services\SimulationService;
use Illuminate\Support\Facades\DB;

class SimulationController extends Controller {

    public function index($week){
        // Leader Table
        $leaderBoard = DB::select("select
        *,
        (( COALESCE(t1.goals_for_home, 0) + COALESCE(t1.goals_for_away,0)) - (COALESCE(t1.goals_against_home,0) + COALESCE(t1.goals_against_away, 0) )) as gd,
        (t1.played - (t1.wins + t1.draws)) as loses,
        ((t1.wins * 3) + (t1.draws * 1)) as pts
        from
        (select
            team_id,
            title,
            logo,
            (select number from weeks where played=1 group by number order by number desc limit 1) as played,
            (select sum(home_score) from weeks where weeks.home_id=team_id) as goals_for_home,
            (select sum(away_score) from weeks where weeks.home_id=team_id) as goals_against_home,
            (select sum(away_score) from weeks where weeks.away_id=team_id) as goals_for_away,
            (select sum(home_score) from weeks where weeks.away_id=team_id) as goals_against_away,
            (select count(row_id) from weeks where (weeks.home_id=team_id and home_score > away_score) or (weeks.away_id=team_id and away_score > home_score)) as wins,
            (select count(row_id) from weeks where (weeks.home_id=team_id or weeks.away_id=team_id) and away_score = home_score) as draws
        from teams
        where team_id in (select home_id from weeks) or team_id in (select away_id from weeks)) as t1 order by pts desc, gd desc, t1.wins desc, loses asc");

        // Current Week Results
        $weekResults = (new Week)->getWeeks($week);

        // Predictions

        $hasPlayed = Week::where('played', '=', 1)->count();

        if($hasPlayed > 0){
            $points = array_map(function($lb){return $lb->pts;},  $leaderBoard);
            $wins   = array_map(function($lb){return $lb->wins;}, $leaderBoard);
            $loses  = array_map(function($lb){return $lb->loses;},$leaderBoard);

            $odds = $this->predict($points, $wins, $loses);
        }else{
            $odds = null;
        }

        return array('leader_board' => $leaderBoard, 'week_results' => $weekResults, 'predications' => $odds);
    }

    public function playAll($all, $week){
        for($i = $week; $i <= $all; $i++){
            $this->play($i);
        }
    }

    public function play($week){
        $ss = new SimulationService;

        $result = $ss->play("MyAlgorithm", $week);

        foreach ($result as $match){
            $weekId = $match['row_id'];
            $homeScore = $match[0]['score'];
            $awayScore = $match[1]['score'];

            Week::where('row_id', '=', $weekId)->update(array('home_score' => $homeScore, 'away_score' => $awayScore, 'played' => 1));
        }

        return $result;
    }

    public function reset(){
        $result = Week::all();

        foreach ($result as $match){
            Week::where('row_id', '=', $match->row_id)->update(array('home_score' => null, 'away_score' => null, 'played' => 0));
        }
    }

    public function resetAll(){
        Week::truncate();
    }

    public function predict($points, $wins, $loses){
        $diff = array_map(function ($x, $y) { return $y-$x; } , $loses, $wins);

        $total = array_sum($points);

        $sdFactor = $this->standardDev([max($points),min($points)]);
        $sdMin = $this->standardDev($points);

        $results = array();

        foreach($points as $k => $p){
            //$percent = 100 * $wins[$k] / ($wins[$k] + $loses[$k]);
            $avg = ($p * 100) / $total + $sdMin;


            if($k == 0){
                $avg = $avg + $sdFactor;
            }

            if($k == count($points) - 1){
                $avg = $avg - $sdFactor;
            }

            array_push($results, abs($avg));
        }

        return $results;
    }

    public function standardDev($arr){
        $num_of_elements = count($arr);
        $variance = 0.0;
        $average = array_sum($arr)/$num_of_elements;

        foreach($arr as $i){
            $variance += pow(($i - $average), 2);
        }

        return (float)sqrt($variance/$num_of_elements);
    }

}
