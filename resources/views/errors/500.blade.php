@extends('errors::terminal')

@section('title', __('Internal Server Error'))
@section('code', '500')
@section('message', 'INTERNAL_SERVER_ERROR')
@section('description', 'The server encountered an internal error and was unable to complete your request.')

@section('terminal')
    <div class="text-neutral-400">
        <span class="text-red-400">[{{ now()->format('Y-m-d H:i:s') }}] production.ERROR:</span> ErrorException: Undefined variable $user in /var/www/html/app/Http/Controllers/UserController.php:42<br>
        <span class="text-neutral-500">Stack trace:</span><br>
        <span class="text-neutral-500">#0 /var/www/html/app/Http/Controllers/UserController.php(42): Illuminate\Foundation\Bootstrap\HandleExceptions->handleError(...)</span><br>
        <span class="text-neutral-500">#1 /var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Controller.php(54): App\Http\Controllers\UserController->index()</span><br>
        <span class="text-red-400">[fatal]</span> Uncaught exception 'ErrorException' with message 'Undefined variable $user'
    </div>
@endsection
