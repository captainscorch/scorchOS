<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/portfolio', function () {
    return Inertia::render('Portfolio');
})->name('portfolio');

Route::get('/case-study/{slug}', function ($slug) {
    return Inertia::render('CaseStudy', [
        'slug' => $slug
    ]);
})->name('case-study');

Route::get('/blog', function () {
    return Inertia::render('Blog');
})->name('blog');

Route::get('/playground', function () {
    return Inertia::render('Playground');
})->name('playground');

Route::get('/blog/{category}/{slug}', function ($category, $slug) {
    return Inertia::render('BlogPost', [
        'slug' => $slug,
        'category' => $category
    ]);
})->name('blog-post');

// Uncomment to test specific error pages
// Route::get('/error-testing', function () {
//     abort(500);
// })->name('error-testing');