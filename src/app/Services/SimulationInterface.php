<?php
namespace App\Services;

use App\Week;

interface SimulationInterface
{
    public function play(int $week);
    public function simulate(Week $week);
}
