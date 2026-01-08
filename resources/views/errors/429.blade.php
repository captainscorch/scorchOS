@extends('errors::terminal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', 'TOO_MANY_REQUESTS')
@section('description', 'You have sent too many requests in a given amount of time.')

@section('terminal')
    <div class="text-neutral-400">
        <span class="text-yellow-400">[warn]</span> 1337#0: *45 limiting requests, excess: 60.000 by zone "api_limit", client: {{ request()->ip() }}, server: scorchos.local, request: "GET /{{ request()->path() }} HTTP/1.1", host: "scorchos.local"<br>
        <span class="text-red-400">[error]</span> Rate limit exceeded for key: {{ request()->ip() }}<br>
        <span class="text-neutral-400">[info]</span> Retry after: 60 seconds<br>
        <span class="text-neutral-500">Connection closed by remote host.</span>
    </div>
@endsection
