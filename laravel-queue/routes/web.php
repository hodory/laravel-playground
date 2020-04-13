<?php

use App\Jobs\ReconcileAccount;
use Illuminate\Pipeline\Pipeline;
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
*/

Route::get('/', function () {
    $pipeline = app(Pipeline::class);

    $pipeline->send('hello freaking world')// data
    ->through([ // pipes
        function ($string, $next) {
            $string = ucwords($string);

            return $next($string);
        },
        function ($string, $next) {
            $string = str_ireplace('freaking', '', $string); // Hello  World

            return $next($string);
        },

        ReconcileAccount::class // Something else
    ])
        ->then(function ($string) {
            dump($string);
        });

    return 'Done';
});
