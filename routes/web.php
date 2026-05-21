<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\CultivateController;
use App\Http\Controllers\HarvestController;
use App\Http\Controllers\WaterController;
use App\Http\Controllers\FertilizeController;
use App\Http\Controllers\ConsumeController;
use App\Http\Controllers\ReportController;
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
// Route::get('/signin', function () {
//     return view('pages.auth.signin', ['title' => 'Sign In']);
// })->name('signin');


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
});

// Route::get('/plants', function () {
//     return view('pages.plants.plants', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');
// Route::get('/plants', [PlantController::class, 'index'])->name('plants');

Route::middleware(['guest'])->group(function () {
    Route::get('/signup', function () {
        return view('pages.auth.signup', ['title' => 'Sign Up']);
    })->name('signup');
    Route::get('/signin', [AuthController::class, 'signin'])->name('signin');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // Semua route di dalam kotak ini aman, wajib login!
    // dashboard pages
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/plants', [PlantController::class, 'index'])->name('plants');
    Route::post('/plants/add', [PlantController::class, 'store'])->name('plants.store');
    Route::put('/plants/edit/{plant}', [PlantController::class, 'update']);
    Route::delete('/plants/delete/{plant}', [PlantController::class, 'destroy']);

    Route::get('/plots', [PlotController::class, 'index'])->name('plots');
    Route::post('/plots/add', [PlotController::class, 'store'])->name('plots.store');
    Route::put('/plots/edit/{plot}', [PlotController::class, 'update']);
    Route::delete('/plots/delete/{plot}', [PlotController::class, 'destroy']);
    Route::get('/plots/cultivate/{plot}', [PlotController::class, 'toCultivate']);

    Route::get('/cultivates', [CultivateController::class, 'index'])->name('cultivates');
    Route::post('/cultivates/add', [CultivateController::class, 'store'])->name('cultivates.store');
    Route::put('/cultivates/edit/{cultivate}', [CultivateController::class, 'update']);
    Route::delete('/cultivates/delete/{cultivate}', [CultivateController::class, 'destroy']);

    Route::get('/harvests', [HarvestController::class, 'index'])->name('harvests');

    Route::post('/waters/add/{cultivate}', [WaterController::class, 'store']);
    Route::delete('/waters/delete/{water}', [WaterController::class, 'destroy']);

    Route::post('/fertilizes/add/{cultivate}', [FertilizeController::class, 'store']);
    Route::delete('/fertilizes/delete/{fertilize}', [FertilizeController::class, 'destroy']);

    Route::post('/harvests/add/{cultivate}', [HarvestController::class, 'store']);
    Route::delete('/harvests/delete/{harvest}', [HarvestController::class, 'destroy']);

    Route::post('/consumes/add/{harvest}', [ConsumeController::class, 'store']);
    Route::delete('/consumes/delete/{consume}', [ConsumeController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::patch('/users/update', [AuthController::class,'updateProfile']);

    Route::put('/profile/password', [AuthController::class, 'updatePassword']);

    Route::get('/reports', [ReportController::class, 'index']);

    Route::get('/reports/excel', [ReportController::class, 'filterUpload']);
});



// Route::get('/plots', function () {
//     return view('pages.plots.plots2', ['title' => 'E-commerce Dashboard']);
// })->name('dashboard');

Route::get('/users', function () {
    return view('pages.users.users', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

Route::get('/settings', function () {
    return view('pages.settings.settings', ['title' => 'E-commerce Dashboard']);
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

















