<?php

namespace App\Services;

class SimulationService{
    public function play($service, $week){
        return app()->make($service)->play($week);
    }
}
