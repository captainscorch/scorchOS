@extends('errors::terminal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', 'PAGE_EXPIRED')
@section('description', 'The page has expired due to inactivity. Please refresh and try again.')

@section('terminal')
    <div class="text-neutral-400">
        <span class="text-red-400">[error]</span> CSRF token mismatch.<br>
        <span class="text-neutral-400">[debug]</span> Session ID: {{ session()->getId() }}<br>
        <span class="text-red-400">[laravel]</span> Illuminate\Session\TokenMismatchException in VerifyCsrfToken.php:85<br>
        <span class="text-neutral-500">Stack trace:</span><br>
        <span class="text-neutral-500">#0 /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php(85): Illuminate\Foundation\Http\Middleware\VerifyCsrfToken->handle(Object(Illuminate\Http\Request), Object(Closure))</span>
    </div>
@endsection
