<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\FixtureService;
use App\Week;
use App\Team;

class WeekController extends Controller
{
    /**
     *
     * @param  Request $body
     * @return int
     */
    public function create(Request $body): int
    {
        $fs = new FixtureService;

        $teams = $body->selectedTeams;

        $fixture = $fs->generateFixtures("RoundRobin", $teams);

        $data = array();

        foreach ($fixture as $key => $week){
            foreach ($week as $matchDay){
                array_push($data, array('number' => $key, 'home_id' => $matchDay[0], 'away_id' => $matchDay[1]));
            }
        }

        Week::truncate();
        Week::insert($data);

        return count($fixture);
    }

    public function edit(Request $body){
        $row_id = $body->row_id;
        $side = $body->side;
        $score = $body->score;

        Week::where('row_id', '=', $row_id)->update(array($side . '_score' => $score));
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(): array
    {
        return (new Week)->getWeeks();
    }
}
