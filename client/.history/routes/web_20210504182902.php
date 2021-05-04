<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


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

Route::get('/', function(){
    return view('welcome');
});

Route::get('/prepare-to-login', function () {
    $state = Str::random(40);
    session([
        'state' => $state
    ]);
    $query = http_build_query([
        'client_id' => env('CLIENT_ID'),
        'redirect_url' => env('REDIRECT_URL'),
        'response_type' => 'code',
        'scope' => '',
        'state' => $state
    ]);
    return redirect('http://localhost:8000/oauth/authorize?'.$query);
})->name('prepare.login');

Route::get('callback', function (Request $request) {
    // dd($request->all());
    //verificacao do state

    $response = Http::post(env('API_URL'). 'oauth/token',[
        'grant_type' => 'authorization_code',
        'client_id' => env('CLIENT_ID'),
        'client_secret' => env('CLIENT_SECRET'),
        'redirect_url' => env('REDIRECT_URL'),
        'code' => $request->code
    ]);
    dd($response->json());
});

Route::get('grant-client', function (Request $request) {
    // dd($request->all());
    //verificacao do state

    $response = Http::post(env('API_URL') . 'oauth/token', [
        'grant_type' => 'client_credentials',
        'client_id' => 4,
        'client_secret' => 'FUYOgU1hJMeBx441V8ooeZbJS6ihi7XMDEQQgOph',
        'redirect_url' => env('REDIRECT_URL'),
        'code' => $request->code
    ]);
    dd($response->json());
});