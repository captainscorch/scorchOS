@extends('errors::terminal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', 'UNAUTHORIZED')
@section('description', 'Access to this resource is denied. Authentication credentials are missing or invalid.')

@section('terminal')
    <div class="text-neutral-400">
        <span class="text-green-400">[info]</span> Checking authentication headers... <span class="text-red-400">failed</span><br>
        <span class="text-red-400">[error]</span> 1337#0: *42 access forbidden by rule, client: {{ request()->ip() }}, server: scorchos.local, request: "GET /{{ request()->path() }} HTTP/2.0", host: "scorchos.local"<br>
        <span class="text-red-400">[security]</span> Invalid or missing Bearer token.<br>
        <span class="text-neutral-500">Connection closed by remote host.</span>
    </div>
@endsection
