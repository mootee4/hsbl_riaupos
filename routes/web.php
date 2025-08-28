<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DataActionController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Camper\CamperController;
use App\Http\Controllers\GoogleController\GoogleController;
use App\Http\Controllers\Publication\PublicationController;
use App\Http\Controllers\Sponsor\SponsorController;
use App\Http\Controllers\TeamVerification\TeamController;
use App\Http\Controllers\User\GalleryController as UserGalleryController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\NewsController;
use App\Http\Controllers\User\PublicationController as UserPublicationController;
use App\Models\TermCondition;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 🔓 ROUTE UMUM (Landing Page)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| 👨‍💼 LOGIN ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 👨‍🎓 LOGIN SISWA (Form + Google)
|--------------------------------------------------------------------------
*/
Route::get('/login-siswa', [SiswaLoginController::class, 'showLoginForm'])->name('login.siswa.form');
Route::post('/login-siswa', [SiswaLoginController::class, 'login'])->name('login.siswa');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| 🛡️ ADMIN AREA (with middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Data Master
    Route::get('/city', [AdminController::class, 'city'])->name('all_data_city');
    Route::post('/city', [AdminController::class, 'storeCity'])->name('city.store');
    Route::post('/city/edit', [DataActionController::class, 'edit'])->name('city.edit');
    Route::post('/city/delete', [DataActionController::class, 'delete'])->name('city.delete');

    Route::get('/data', [AdminController::class, 'allData'])->name('all_data');
    Route::post('/add-data', [AdminController::class, 'storeData'])->name('data.store');
    Route::post('/data/edit', [DataActionController::class, 'edit'])->name('data.edit');
    Route::post('/data/delete', [DataActionController::class, 'delete'])->name('data.delete');
    Route::get('/data/{type}', [DataActionController::class, 'index'])->where('type', 'school|venue|match|award')->name('data.dynamic');

    Route::resource('/school', AdminController::class)->only(['index', 'store']);
    Route::get('/school/{id}/edit', [AdminController::class, 'editSchool'])->name('school.edit');
    Route::put('/school/{id}', [AdminController::class, 'updateSchool'])->name('school.update');

    Route::get('/venue', [AdminController::class, 'venue'])->name('all_data_venue');
    Route::post('/venue', [AdminController::class, 'storeVenue'])->name('venue.store');
    Route::get('/venue/{id}/edit', [AdminController::class, 'editVenue'])->name('venue.edit');
    Route::put('/venue/{id}', [AdminController::class, 'updateVenue'])->name('venue.update');

    Route::get('/award', [AdminController::class, 'award'])->name('all_data_award');
    Route::post('/award', [AdminController::class, 'storeAward'])->name('award.store');
    Route::get('/award/{id}/edit', [AdminController::class, 'editAward'])->name('award.edit');
    Route::put('/award/{id}', [AdminController::class, 'updateAward'])->name('award.update');

    Route::get('/export/{type}', [DataActionController::class, 'export'])->where('type', 'school|venue|match|award')->name('data.export');

    // Verifikasi
    Route::get('/team-list', [TeamController::class, 'teamList'])->name('tv_team_list');
    Route::get('/team-list/{id}', [TeamController::class, 'teamShow'])->name('team-list.show');
    Route::get('/team-verification', [TeamController::class, 'teamVerification'])->name('tv_team_verification');
    Route::get('/team-awards', [TeamController::class, 'teamAwards'])->name('tv_team_awards');

    // Camper
    Route::get('/camper', [CamperController::class, 'camper'])->name('camper_team');
    Route::get('/camper/detail/{id}', [CamperController::class, 'camperDetail'])->name('camper.detail');
    Route::post('/camper/detail/update/{id}', [CamperController::class, 'updateCamper'])->name('camper.update');

    // Match / Schedule
    Route::get('/schedule', [PublicationController::class, 'match'])->name('pub_schedule');
    Route::post('/schedule', [PublicationController::class, 'storeMatch'])->name('match.store');
    Route::get('/schedule/{id}/edit', [PublicationController::class, 'editMatch'])->name('match.edit');
    Route::put('/schedule/{id}', [PublicationController::class, 'updateMatch'])->name('match.update');
    Route::post('/publication/schedule/publish/{id}', [PublicationController::class, 'publish'])->name('match.publish');
    Route::post('/publication/schedule/unpublish/{id}', [PublicationController::class, 'unpublish'])->name('match.unpublish');
    Route::post('/publication/schedule/done/{id}', [PublicationController::class, 'done'])->name('match.done');

    // Result
    Route::get('/result', [PublicationController::class, 'result'])->name('pub_result');
    Route::post('/result', [PublicationController::class, 'storeResult'])->name('result.store');
    Route::get('/result/{id}/edit', [PublicationController::class, 'editResult'])->name('result.edit');
    Route::put('/result/{id}', [PublicationController::class, 'updateResult'])->name('result.update');
    Route::post('/publication/result/publish/{id}', [PublicationController::class, 'publish'])->name('result.publish');

    // Event
    Route::get('/event', [PublicationController::class, 'event'])->name('pub_event');
    Route::post('/event', [PublicationController::class, 'storeEvent'])->name('event.store');
    Route::get('/event/{id}/edit', [PublicationController::class, 'editEvent'])->name('event.edit');
    Route::put('/event/{id}', [PublicationController::class, 'updateEvent'])->name('event.update');
    Route::post('/publication/event/publish/{id}', [PublicationController::class, 'publishEvent'])->name('event.publish');

    // Sponsor
    Route::get('/sponsor', [SponsorController::class, 'sponsor'])->name('sponsor.sponsor');
    Route::post('/sponsor', [SponsorController::class, 'store'])->name('sponsor.store');
    Route::put('/sponsor/{id}', [SponsorController::class, 'update'])->name('sponsor.update');
    Route::delete('/sponsor/{id}', [SponsorController::class, 'destroy'])->name('sponsor.destroy');
    Route::post('/sponsor/destroy-selected', [SponsorController::class, 'destroySelected'])->name('sponsor.destroySelected');

    // News
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{id}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    Route::get('/term-conditions', [TermConditionController::class, 'index'])->name('term_conditions.index');
    Route::post('/term-conditions', [TermConditionController::class, 'store'])->name('term_conditions.store');
    Route::delete('/term-conditions/delete-selected', [TermConditionController::class, 'destroySelected'])->name('term_conditions.destroySelected');
    Route::delete('/term-conditions/{id}', [TermConditionController::class, 'destroy'])->name('term_conditions.destroy');

    // Videos
    Route::get('videos', [AdminGalleryController::class, 'index'])->name('videos.index');
    Route::get('videos/create', [AdminGalleryController::class, 'create'])->name('videos.create');
    Route::post('videos', [AdminGalleryController::class, 'store'])->name('videos.store');
    Route::get('videos/{video}/edit', [AdminGalleryController::class, 'edit'])->name('videos.edit');
    Route::put('videos/{video}', [AdminGalleryController::class, 'update'])->name('videos.update');
    Route::delete('videos/{video}', [AdminGalleryController::class, 'destroy'])->name('videos.destroy');

});

/*
|--------------------------------------------------------------------------
| 👥 ROUTE USER / SISWA (PUBLIC - tanpa login)
|--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{id}', [NewsController::class, 'show'])->whereNumber('id')->name('news.show');

    Route::get('/schedules-results', [UserPublicationController::class, 'scheduleResult'])->name('schedule_result');

    // ✅ Download dokumen SnK
    Route::get('/download-terms', function () {
        $latestTerm = TermCondition::orderBy('year', 'desc')->first();

        if (! $latestTerm || ! Storage::disk('public')->exists($latestTerm->file_path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download($latestTerm->file_path, 'SyaratKetentuan-' . $latestTerm->year . '.pdf');
    })->name('download_terms');

  Route::get('/videos', [UserGalleryController::class, 'videos'])->name('videos');
    Route::get('/videos/{slug}', [UserGalleryController::class, 'videoDetail'])->name('videos.detail');});
