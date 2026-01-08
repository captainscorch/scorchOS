@extends('errors::terminal')

@section('title', __('Payment Required'))
@section('code', '402')
@section('message', 'PAYMENT_REQUIRED')
@section('description', 'The requested action requires a valid payment method. Transaction was declined.')

@section('terminal')
    <div class="text-neutral-400">
        <span class="text-green-400">[info]</span> Initiating transaction... <span class="text-yellow-400">processing</span><br>
        <span class="text-red-400">[error]</span> Payment gateway rejected transaction: insufficient_funds<br>
        <span class="text-neutral-400">[debug]</span> Transaction ID: tx_{{ substr(md5(time()), 0, 8) }}<br>
        <span class="text-red-400">[fatal]</span> Subscription status: <span class="text-red-500 font-bold">INACTIVE</span>
    </div>
@endsection
