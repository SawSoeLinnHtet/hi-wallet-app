@extends('frontend.layouts.app')

@section('title', 'Wallet')

@section('content')
    <div class="wallet">
        <div class="card my-card">
            <div class="card-body">
                <div class="mb-3">
                    <span>Balance</span>
                    <h4>{{ number_format($auth_user->Wallet->amount ) }} <span class="currency">MMK</span> </h4>
                </div>
                <div class="mb-3">
                    <span>Account Number</span>
                    <h5>{{ $auth_user->Wallet ? $auth_user->Wallet->account_number : '-' }}</h5>
                </div>
                <div class="">
                    <p>
                        {{ $auth_user->name }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection