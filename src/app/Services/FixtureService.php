<?php
namespace App\Services;

class FixtureService {
    public function generateFixtures($service, $teams){
        return app()->make($service)->generateFixtures($teams);
    }
}
