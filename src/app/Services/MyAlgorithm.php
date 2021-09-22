<?php

namespace App\Services;

use App\Team;
use App\Week;

class MyAlgorithm implements SimulationInterface {

    private $maxWei = 300;
    private $range  = 1000;
    private $tirednessFactor = 1.25;
    private $depressionRatio = 10;
    private $motivationRatio = 15;
    private $chanceMinutes = [0, 15, 30, 45, 60, 75, 90];

    public function play(int $week){
        $matches = Week::where('number', '=', $week)->get();

        $results = array();

        foreach ($matches as $match){
            $weekResults = $this->simulate($match);
            array_push($results, $weekResults);
        }

        return $results;
    }

    public function simulate(Week $week){
        // ------ Get home team strengths and calculate mental condition by last 2 games. -----
        $homeTeam = Team::where('team_id', '=', $week->home_id)->first();

        // avg
        $homeAvg  = $this->getTeamAvg($homeTeam);

        // condition ratio by last 2 match (based avg)
        $homeCon  = $this->getTeamConditionBy($week->home_id);
        $homeAvg  = $this->increaseRatio($homeAvg, $homeCon);

        // home advantage (based home condition avg)
        $homeHACon = $this->getTeamConditionBy($week->home_id, "home");
        $homeAvg  = $this->increaseRatio($homeAvg, $homeHACon);

        // ------ Get away team strengths and calculate mental condition by last 2 games. -----
        $awayTeam = Team::where('team_id', '=', $week->away_id)->first();
        $awayAvg  = $this->getTeamAvg($awayTeam);
        $awayCon  = $this->getTeamConditionBy($week->away_id);
        $awayAvg  = $this->increaseRatio($awayAvg, $awayCon);
        $awayAHCon = $this->getTeamConditionBy($week->away_id, "away");
        $awayAvg  = $this->increaseRatio($awayAvg, $awayAHCon);

        // decreasing goal chances at 15, 30, 45, 60, 75, 90 minutes by
        // if goal avg increase opposite avg decrease

        $homeScore = 0;
        $awayScore = 0;

        // re-calculate chance ranges consider with tiredness/goal motivation or goal depression
        foreach ($this->chanceMinutes as $minute){
            $downCondition = $minute / $this->tirednessFactor;

            $homeAvg  = $this->decreaseRatio($homeAvg, $downCondition);
            $awayAvg  = $this->decreaseRatio($awayAvg, $downCondition);

            if($homeScore > $awayScore){
                $awayAvg  = $this->decreaseRatio($awayAvg, $this->depressionRatio);
                $homeAvg  = $this->increaseRatio($homeAvg, $this->motivationRatio);
            }

            if($awayScore > $homeScore){
                $homeAvg  = $this->decreaseRatio($homeAvg, $this->depressionRatio);
                $awayAvg  = $this->increaseRatio($awayAvg, $this->motivationRatio);
            }

            $homeChance = $this->getRange($homeAvg);
            $awayChance = $this->getRange($awayAvg);

            $chances = [$homeChance, $awayChance];
            $min = min($chances);
            $max = max($chances);

            srand(random_int(PHP_INT_MIN, PHP_INT_MAX));
            $chance = rand(0, $max + $min);

            if (in_array($chance, range(0, $min)) ) {
                // which team is min?
                if($homeChance < $awayChance){
                    // home
                    if($this->isGoal($min, $max)){
                        $homeScore++;
                    }
                }elseif($awayChance < $homeChance){
                    // away
                    if($this->isGoal($min, $max)){
                        $awayScore++;
                    }
                }
            }elseif (in_array($chance, range($max, 750)) ) {
                // which team is max?
                if($homeChance > $awayChance){
                    // home
                    if($this->isGoal($min, $max)){
                        $homeScore++;
                    }
                }elseif($awayChance > $homeChance){
                    // away
                    if($this->isGoal($min, $max)){
                        $awayScore++;
                    }
                }
            }
        }

        return array(
            'row_id' => $week->row_id,
            array('home' => $homeTeam, 'score' => $homeScore),
            array('away' => $awayTeam, 'score' => $awayScore)
        );
    }

    public function isGoal($min, $max): bool{
        srand(random_int(PHP_INT_MIN, PHP_INT_MAX));
        $shoot = rand(0, $this->range);

        return in_array($shoot, range(0, $max + $min));
    }

    public function getRange($avg){
        return ceil(($avg * $this->range) / 100);
    }

    public function increaseRatio($avg, $extraRatio){
        return $avg + ($avg * $extraRatio / 100);
    }

    public function decreaseRatio($avg, $extraRatio){
        return $avg - ($avg * $extraRatio / 100);
    }

    public function getTeamAvg($team){
        $wei  = ($team->def + $team->mid + $team->att) / 3;
        $avg  = ($wei * 100) / $this->maxWei;

        return $avg;
    }

    public function getTeamConditionBy($teamId, $side = "all", $matches = 2){
        // side = all | home | away

        if($side == "all"){
            $result = Week::where(function($query) use ($teamId){
                return $query->where('home_id', '=', $teamId)->orWhere('away_id', '=', $teamId);
            })
                ->where(function($query){
                    return $query->where('played', '=', 1);
                })
                ->orderBy('number', 'desc')
                ->limit($matches)
                ->get();
        }

        if($side == "home"){
            $result = Week::where('home_id', '=', $teamId)
                ->where('played', '=', 1)
                ->orderBy('number', 'desc')
                ->limit($matches)
                ->get();
        }


        if($side == "away"){
            $result = Week::where('away_id', '=', $teamId)
                ->where('played', '=', 1)
                ->orderBy('number', 'desc')
                ->limit($matches)
                ->get();
        }

        $last_games = array();

        foreach ($result as $game){
            // on win
            if($game->home_id == $teamId && $game->home_score > $game->away_score){
                array_push($last_games, 1);
            }

            if($game->away_id == $teamId && $game->away_score > $game->home_score){
                array_push($last_games, 1);
            }

            // on lose
            if($game->home_id == $teamId && $game->home_score < $game->away_score){
                array_push($last_games, 0);
            }

            if($game->away_id == $teamId && $game->away_score < $game->home_score){
                array_push($last_games, 0);
            }

            // on draw
            if($game->home_score == $game->away_score){
                array_push($last_games, 0.5);
            }
        }

        return (array_sum($last_games) * 100) / $matches;
    }
}
