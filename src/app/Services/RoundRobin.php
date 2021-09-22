<?php

namespace App\Services;

use Exception;
/*
|
| In this class you can find the generateFixtures and private flip methods.
|
*/

class RoundRobin implements FixtureInterface {

    /**
     * @throws \Exception
     */
    public function generateFixtures($teams): array {
        $teamCount = count($teams);
        $weeks = ($teamCount - 1) * 2;

        // check team count odd
        if($teamCount % 2 !== 0){
            throw new Exception("Teams count must be even.");
        }

        // do more randomize and shuffle teams
        srand(random_int(PHP_INT_MIN, PHP_INT_MAX));
        shuffle($teams);

        $fixtures = [];

        for($week = 1; $week <= $weeks; $week += 1) {
            foreach($teams as $key => $team) {

                if($key >= $teamCount / 2) {
                    break;
                }

                $team1 = $team;
                $team2 = $teams[$key + $teamCount / 2];

                $matchDay = $week % 2 === 0 ? [$team1, $team2] : [$team2, $team1];
                $fixtures[$week][] = $matchDay;
            }

            // After half season mirror the teams
            $this->flip($teams);
        }

        return $fixtures;
    }

    // The flip function rotates the array inline
    private function flip(array &$items){
        $itemCount = count($items);

        if($itemCount < 3) {
            return;
        }

        $lastIndex = $itemCount - 1;
        $factor = (int) ($itemCount % 2 === 0 ? $itemCount / 2 : ($itemCount / 2) + 1);
        $topRightIndex = $factor - 1;
        $topRightItem = $items[$topRightIndex];
        $bottomLeftIndex = $factor;
        $bottomLeftItem = $items[$bottomLeftIndex];

        for($i = $topRightIndex; $i > 0; $i -= 1) {
            $items[$i] = $items[$i - 1];
        }

        for($i = $bottomLeftIndex; $i < $lastIndex; $i += 1) {
            $items[$i] = $items[$i + 1];
        }

        $items[1] = $bottomLeftItem;
        $items[$lastIndex] = $topRightItem;
    }
}
