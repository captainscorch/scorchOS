@extends('errors::terminal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', 'SERVICE_UNAVAILABLE')
@section('description', 'The server is currently unavailable (because it is overloaded or down for maintenance).')

@section('terminal')
    <div class="text-neutral-400">
        <span class="text-red-400">[error]</span> 1337#0: *46 connect() to unix:/var/run/php/php8.2-fpm.sock failed (11: Resource temporarily unavailable)<br>
        <span class="text-neutral-400">client: {{ request()->ip() }}, server: scorchos.local</span><br>
        <span class="text-red-400">[upstream]</span> upstream timed out (110: Connection timed out) while reading response header from upstream<br>
        <span class="text-yellow-400">[maintenance]</span> System is currently in maintenance mode.
    </div>
@endsection
