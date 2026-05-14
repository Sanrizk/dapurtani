<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\CultivateController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\WaterController;
use App\Http\Controllers\FertilizeController;

// dashboard pages
Route::get('/', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');


Route::get('/dashboard2', function () {
    return view('pages.dashboard_.dashboard', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

// Route::get('/plants', function () {
//     return view('pages.plants.plants', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');
// Route::get('/plants', [PlantController::class, 'index'])->name('plants');
Route::get('/plants', [PlantController::class, 'index'])->name('plants');
Route::post('/plants/add', [PlantController::class, 'store'])->name('plants.store');
Route::put('/plants/edit/{plant}', [PlantController::class,'update']);
Route::delete('/plants/delete/{plant}', [PlantController::class,'destroy']);

Route::get('/plots', [PlotController::class, 'index'])->name('plots');
Route::post('/plots/add', [PlotController::class, 'store'])->name('plots.store');
Route::put('/plots/edit/{plot}', [PlotController::class,'update']);
Route::delete('/plots/delete/{plot}', [PlotController::class,'destroy']);

Route::get('/cultivates', [CultivateController::class, 'index'])->name('cultivates');
Route::post('/cultivates/add', [CultivateController::class, 'store'])->name('cultivates.store');
Route::put('/cultivates/edit/{cultivate}', [CultivateController::class,'update']);
Route::delete('/cultivates/delete/{cultivate}', [CultivateController::class,'destroy']);

Route::get('/harvests', [HarvestController::class, 'index'])->name('harvests');

Route::post('/waters/add/{cultivate}', [WaterController::class,'store']);

Route::post('/fertilizes/add/{cultivate}', [FertilizeController::class,'store']);

Route::post('/harvests/add/{cultivate}', [HarvestController::class,'store']);

// Route::get('/plots', function () {
//     return view('pages.plots.plots2', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');

Route::get('/users', function () {
    return view('pages.users.users', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

// Route::get('/cultivates', function () {
//     return view('pages.cultivates.cultivates2', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');

Route::get('/fertilizes', function () {
    return view('pages.fertilizes.fertilizes', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

Route::get('/waters', function () {
    return view('pages.waters.waters', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

// Route::get('/harvests', function () {
//     return view('pages.harvests.harvests2', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');

Route::get('/consumes', function () {
    return view('pages.consumes.consumes', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

















