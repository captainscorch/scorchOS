@extends('errors::terminal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', 'FORBIDDEN')
@section('description', 'You do not have permission to access this resource on this server.')

@section('terminal')
    <div class="text-neutral-400">
        <span class="text-red-400">[error]</span> 1337#0: *43 directory index of "/var/www/html/{{ request()->path() }}/" is forbidden<br>
        <span class="text-neutral-400">client: {{ request()->ip() }}, server: scorchos.local</span><br>
        <span class="text-neutral-400">request: "GET /{{ request()->path() }} HTTP/1.1", host: "scorchos.local"</span><br>
        <span class="text-red-400">[core:error]</span> [pid 1234] [client {{ request()->ip() }}] AH00035: access to /var/www/html/{{ request()->path() }} denied (filesystem path '/var/www/html/{{ request()->path() }}') because search permissions are missing on a component of the path
    </div>
@endsection
