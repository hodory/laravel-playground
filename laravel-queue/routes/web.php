<?php

use App\Jobs\ReconcileAccount;
use App\User;
use Illuminate\Bus\Dispatcher;
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
    $user = factory(User::class)->create();

    $job = new ReconcileAccount($user);

    resolve(Dispatcher::class)->dispatch($job);

    return 'Done';
});
