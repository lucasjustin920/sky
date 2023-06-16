<?php

use Illuminate\Support\Facades\Route;
use LaraZeus\Sky\Http\Livewire\Faq;
use LaraZeus\Sky\Http\Livewire\Library;
use LaraZeus\Sky\Http\Livewire\LibraryItem;
use LaraZeus\Sky\Http\Livewire\Page;
use LaraZeus\Sky\Http\Livewire\Post;
use LaraZeus\Sky\Http\Livewire\Posts;
use LaraZeus\Sky\Http\Livewire\Tags;

if (in_array('LaraZeus\Sky\Filament\Resources\FaqResource', config('zeus-sky.enabled_resources'))) {
    Route::middleware(config('zeus-sky.middleware'))
        ->get(config('zeus-sky.faq_uri_prefix'), Faq::class)
        ->name('faq');
}

if (in_array('LaraZeus\Sky\Filament\Resources\LibraryResource', config('zeus-sky.enabled_resources'))) {
    Route::middleware(config('zeus-sky.middleware'))
        ->prefix(config('zeus-sky.library_uri_prefix'))
        ->group(function () {
            Route::get('/', Library::class)->name('library');
            Route::get('/{slug}', LibraryItem::class)->name('library.item');
        });
}

Route::prefix(config('zeus-sky.path'))
    ->middleware(config('zeus-sky.middleware'))
    ->group(function () {
        Route::get('/', Posts::class)->name('blogs');
        Route::get(config('zeus-sky.post_uri_prefix') . '/{slug}', Post::class)->name('post');
        Route::get(config('zeus-sky.page_uri_prefix') . '/{slug}', Page::class)->name('page');
        Route::get('{type}/{slug}', Tags::class)->name('tags');

        Route::get('passConf', function () {
            session()->put(request('postID') . '-' . request('password'), request('password'));

            return redirect()->back()->with('status', 'sorry, password incorrect!');
        });
    });
