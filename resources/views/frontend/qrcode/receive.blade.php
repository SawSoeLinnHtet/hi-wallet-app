@extends('frontend.layouts.app')

@section('title', 'Receive QR')

@section('content')
    <div class="receive-qr">
        <div class="card">
            <div class="card-body p-2 px-3">
                <p class="text-center mb-0 fw-bold" style="font-size: 18px">QR Scan to pay me</p>
                <div class="text-center mb-0">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(240)->generate($auth_user->phone)) !!} ">
                </div>
                <p class="text-center mb-1" style="font-size: 15px">
                    <strong>
                        {{ $auth_user->name }}
                    </strong>
                </p>
                <p class="text-center mb-1">{{ $auth_user->phone }}</p>
            </div>
        </div>
    </div>
@endsection