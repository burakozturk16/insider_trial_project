<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


https://php-ml.readthedocs.io/en/latest/machine-learning/classification/svc/
https://www.youtube.com/watch?v=vLt6FtAeS8s
https://towardsdatascience.com/machine-learning-algorithms-for-football-prediction-using-statistics-from-brazilian-championship-51b7d4ea0bc8

Probability

*/

Route::get('/', function () {
    return view('app');
});
