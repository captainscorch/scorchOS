@extends('errors::terminal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', 'NOT_FOUND')
@section('description', 'The requested resource could not be found on this server.')

@section('terminal')
    <div class="text-neutral-400">
        <span class="text-red-400">[error]</span> 1337#0: *44 open() "/var/www/html/public/{{ request()->path() }}" failed (2: No such file or directory)<br>
        <span class="text-neutral-400">client: {{ request()->ip() }}, server: scorchos.local</span><br>
        <span class="text-neutral-400">request: "GET /{{ request()->path() }} HTTP/1.1", host: "scorchos.local"</span><br>
        <span class="text-yellow-400">[warn]</span> URI segment "{{ request()->path() }}" did not match any known routes.<br>
        <span class="text-red-400">[laravel]</span> NotFoundHttpException in RouteCollection.php:179
    </div>
@endsection
