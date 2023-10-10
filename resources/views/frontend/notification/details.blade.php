@extends('frontend.layouts.app')

@section('title', 'Notification Details')

@section('content')
    <div class="notification">
        <div class="card mb-2 py-2">
            <div class="card-body p-2 px-3">
                <div class="text-center mb-4">
                    <img src="{{ asset('frontend/img/notification.svg') }}" style="width: 220px" alt="">
                </div>
                <div class="text-left">
                    <h6 class="fw-bolder">
                        {{ $notification->data['title'] }}
                    </h6>
                    <p class="mb-2">
                        {{ $notification->data['message'] }}
                    </p>
                    <p class="mb-3 text-muted">
                        <small>
                            {{ Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A') }}
                        </small>
                    </p>

                    <a href="{{ $notification->data['web_link'] }}" class="btn btn-theme btn-sm">
                        Continue <i class="fas fa-long-arrow-alt-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection