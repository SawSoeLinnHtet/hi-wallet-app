@extends('frontend.layouts.app')

@section('title', 'Hi Wallet')
@section('content')

    <div class="home-page">
        <div class="row">
            <div class="col-12">
                <div class="profile mb-3">
                    <img src="https://ui-avatars.com/api/?background=5842e3&color=fff&name={{ Auth::user()->name }}" alt="profile">
                    <h5>{{ auth()->user()->name }}</h5>
                    <p class="text-muted">{{ number_format(auth()->user()->Wallet ? auth()->user()->Wallet->amount : '0') }} MMK</p>
                </div>
            </div>
            <div class="col-6">
                <a href="{{ route('get-scan-and-pay') }}">
                    <div class="card shortcut-box mb-3">
                        <div class="card-body p-3">
                            <img src="{{ asset('frontend/img/qr-code-scan.png') }}" alt="">
                            <span>
                                Scan & Pay
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('get-receive-qr') }}">
                    <div class="card shortcut-box mb-3">
                        <div class="card-body p-3">
                            <img src="{{ asset('frontend/img/qr-code.png') }}" alt="">
                            <span>
                                Receive QR
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12">
                <div class="card mb-3 function-box">
                    <div class="card-body pe-0">
                        <div class="point">
                            <a href="{{ route('get-wallet-transfer') }}" class="d-flex justify-content-between">
                                <span>
                                    <img src="{{ asset('frontend/img/money-transfer.png') }}" alt="">
                                    Transfer
                                </span>
                                <span class="me-3">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                        <hr>
                        <div class="point">
                            <a href="{{ route('get-wallet-index') }}" class="d-flex justify-content-between">
                                <span>
                                    <img src="{{ asset('frontend/img/wallet.png') }}" alt="">
                                    Wallet
                                </span>
                                <span class="me-3">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                        <hr>
                        <div class="point">
                            <a href="{{ route('get-transaction-index') }}" class="d-flex justify-content-between">
                                <span>
                                    <img src="{{ asset('frontend/img/mobile-transfer.png') }}" alt="">
                                    Transaction
                                </span>
                                <span class="me-3">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
