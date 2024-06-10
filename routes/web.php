<?php

use App\Http\Middleware\CountViews;
use Illuminate\Support\Facades\Route;

Route::middleware([CountViews::class])->group(function () {
    Route::get('/nieruchomosci/{slug}', [App\Http\Controllers\PropertiesController::class, 'index'])->name('properties.index');
    Route::get('/komunikaty/{slug}', [App\Http\Controllers\ComunicatsController::class, 'index'])->name('comunicats.index');
    Route::get('/ruchomosci/{slug}', [App\Http\Controllers\MovablePropertyController::class, 'index'])->name('movable.index');
    Route::get('/wierzytelnosci/{slug}', [App\Http\Controllers\ClaimController::class, 'index'])->name('wierzytelnosci.index');
});
Route::get('/print/{slug}', [App\Http\Controllers\PropertiesController::class, 'printPage'])->name('properties.printPage');
Route::get('/print-movable/{slug}', [App\Http\Controllers\MovablePropertyController::class, 'printPage'])->name('movable.printPage');
Route::get('/print-comunicats/{slug}', [App\Http\Controllers\ComunicatsController::class, 'printPage'])->name('comunicats.printPage');
Route::get('/print-claim/{slug}', [App\Http\Controllers\ClaimController::class, 'printPage'])->name('claim.printPage');

Route::get('/komunikaty', [App\Http\Controllers\SearchController::class, 'komunikaty'])->name('search.komunikaty');
Route::get('/nieruchomosci', [App\Http\Controllers\SearchController::class, 'propertiesList'])->name('search.nieruchomosci');
Route::get('/ruchomosci', [App\Http\Controllers\SearchController::class, 'ruchomosci'])->name('search.ruchomosci');
Route::get('/wierzytelnosci', [App\Http\Controllers\SearchController::class, 'wierzytelnosci'])->name('search.wierzytelnosci');

// NEWS
Route::get('/news/{slug}', [App\Http\Controllers\NewsController::class, 'view'])->name('news.view');
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');

// PAGES
Route::get('/p/{slug}', [App\Http\Controllers\PagesController::class, 'view'])->name('pages.view');

Route::get('/', [App\Http\Controllers\PagesController::class, 'index'])->name('pages.index');

// basic
Route::get('/kontakt', [App\Http\Controllers\PagesController::class, 'kontakt'])->name('pages.kontakt');
Route::get('/page/kontakt', [App\Http\Controllers\PagesController::class, 'kontakt'])->name('pages.kontakt');
Route::get('/sklepy', [App\Http\Controllers\PagesController::class, 'shops'])->name('pages.shops');
Route::get('/kategorie', [App\Http\Controllers\PagesController::class, 'categories'])->name('pages.categories');
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'open'])->name('blog.open');
Route::get('/kod/{id}', [App\Http\Controllers\CodesController::class, 'open'])->name('codes.open');

// catch-all route for any controller/action
Route::get('/{controller}/{action}', function ($controller, $action) {
    $controller = ucfirst($controller) . 'Controller';
    $controller = "App\\Http\\Controllers\\{$controller}";
    return app()->call([$controller, $action]);
});

// Routes for uploadFile
Route::prefix('uploadFile')->group(function () {
    Route::get('/{action}/{id}', [App\Http\Controllers\FileController::class, 'action'])->where('id', '[0-9]+')->name('file.action');
    Route::get('/{action}', [App\Http\Controllers\FileController::class, 'action'])->name('file.action');
});
Route::prefix('canvas-ui')->group(function () {
    Route::prefix('api')->group(function () {
        Route::get('posts', [\App\Http\Controllers\CanvasUiController::class, 'getPosts']);
        Route::get('posts/{slug}', [\App\Http\Controllers\CanvasUiController::class, 'showPost'])
            ->middleware('Canvas\Http\Middleware\Session');

        Route::get('tags', [\App\Http\Controllers\CanvasUiController::class, 'getTags']);
        Route::get('tags/{slug}', [\App\Http\Controllers\CanvasUiController::class, 'showTag']);
        Route::get('tags/{slug}/posts', [\App\Http\Controllers\CanvasUiController::class, 'getPostsForTag']);

        Route::get('topics', [\App\Http\Controllers\CanvasUiController::class, 'getTopics']);
        Route::get('topics/{slug}', [\App\Http\Controllers\CanvasUiController::class, 'showTopic']);
        Route::get('topics/{slug}/posts', [\App\Http\Controllers\CanvasUiController::class, 'getPostsForTopic']);

        Route::get('users/{id}', [\App\Http\Controllers\CanvasUiController::class, 'showUser']);
        Route::get('users/{id}/posts', [\App\Http\Controllers\CanvasUiController::class, 'getPostsForUser']);
    });

    Route::get('/{view?}', [\App\Http\Controllers\CanvasUiController::class, 'index'])
        ->where('view', '(.*)')
        ->name('canvas-ui');
});
