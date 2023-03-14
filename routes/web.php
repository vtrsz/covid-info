<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $response = Http::get('https://dev.kidopilabs.com.br/exercicio/covid.php?listar_paises=1');
    $countries = json_decode($response->getBody());

    //$lastAppointment = DB::table('last_appointment_time')->latest('updated_at')->first();

    return view('home', compact('countries'));
});

Route::get('/result', function (Request $request) {
    $countryFound = false;

    $response = Http::get('https://dev.kidopilabs.com.br/exercicio/covid.php?listar_paises=1');
    $countries = json_decode($response->getBody());

    $country = str_replace("_", " ", $request->input('country'));

    if (!$request->has('country')) {
        return redirect('/')->with('searchCountry-error', 'Country needs to be informed.');
    }

    foreach($countries as $countryAccepted) {
        if (strcmp($countryAccepted, $country) == 0) {
            $countryFound = true;
        }
    }

    if (!$countryFound) {
        return redirect('/')->with('searchCountry-error', 'This country ('. $country .') is not supported.');
    }

    $response = Http::get('https://dev.kidopilabs.com.br/exercicio/covid.php?pais='. $country);
    $data = json_decode($response->getBody());

    if (!Schema::hasTable('last_appointment_time')){
        Schema::create('last_appointment_time', function ($table) {
            $table->string('country');
            $table->dateTime('updated_at');
        });
    }

    DB::table('last_appointment_time')->insert([
        'country' => $country,
        'updated_at' => now()->timezone('America/Sao_Paulo'),
    ]);

    return view('country-result', compact('data'));
})->name('country-result');

Route::get('/comparison', function (Request $request) {
    $firstCountryFound = false;
    $secondCountryFound = false;

    $response = Http::get('https://dev.kidopilabs.com.br/exercicio/covid.php?listar_paises=1');
    $countries = json_decode($response->getBody());

    $firstCountry = str_replace("_", " ", $request->input('firstCountry'));
    $secondCountry = str_replace("_", " ", $request->input('secondCountry'));

    if (!$request->has('firstCountry')) {
        return redirect('/')->with('compareCountry-error', 'firstCountry needs to be informed.');
    } elseif (!$request->has('secondCountry')) {
        return redirect('/')->with('compareCountry-error', 'secondCountry needs to be informed.');
    }

    foreach($countries as $countryAccepted) {
        if (strcmp($countryAccepted, $firstCountry) == 0) {
            $firstCountryFound = true;
        }
        if (strcmp($countryAccepted, $secondCountry) == 0) {
            $secondCountryFound = true;
        }
    }

    if (!$firstCountry) {
        return redirect('/')->with('compareCountry-error', 'This firstCountry ('. $firstCountry .')  is not supported.');
    } elseif (!$secondCountryFound) {
        return redirect('/')->with('compareCountry-error', 'This secondCountry ('. $secondCountry .') is not supported.');
    }

    if ($firstCountry == $secondCountry) {
        return redirect('/')->with('compareCountry-error', 'The countries must be different!');
    }

    $firstCountryResponse = Http::get('https://dev.kidopilabs.com.br/exercicio/covid.php?pais='. $firstCountry);
    $firstCountryData = json_decode($firstCountryResponse->getBody());

    $secondCountryResponse = Http::get('https://dev.kidopilabs.com.br/exercicio/covid.php?pais='. $secondCountry);
    $secondCountryData = json_decode($secondCountryResponse->getBody());

    if (!Schema::hasTable('last_comparison_time')){
        Schema::create('last_comparison_time', function ($table) {
            $table->string('first_country');
            $table->string('second_country');
            $table->dateTime('updated_at');
        });
    }

    DB::table('last_comparison_time')->insert([
        'first_country' => $firstCountry,
        'second_country' => $secondCountry,
        'updated_at' => now()->timezone('America/Sao_Paulo'),
    ]);

    $firstCountryTotalConfirmed = 0;
    $firstCountryTotalDeaths = 0;

    foreach ($firstCountryData as $firstCountry){
        $firstCountryTotalDeaths += $firstCountry->Confirmados;
        $firstCountryTotalConfirmed += $firstCountry->Mortos;
    }

    $secondCountryTotalConfirmed = 0;
    $secondCountryTotalDeaths = 0;

    foreach ($secondCountryData as $secondCountry){
        $secondCountryTotalDeaths += $secondCountry->Confirmados;
        $secondCountryTotalConfirmed += $secondCountry->Mortos;
    }

    $firstCountryRate = $firstCountryTotalDeaths / $firstCountryTotalConfirmed;
    $secondCountryRate = $secondCountryTotalDeaths / $secondCountryTotalConfirmed;

    $result = $firstCountryRate - $secondCountryRate;

    $data = array(
        'firstCountry' => $firstCountry->Pais,
        'firstCountryRate' => $firstCountryRate,
        'secondCountry' => $secondCountry->Pais,
        'secondCountryRate' => $secondCountryRate,
        'result' => $result
    );

    return view('compare-result', compact('data'));
})->name('compare-result');
