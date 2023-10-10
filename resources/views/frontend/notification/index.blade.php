@extends('frontend.layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="notification">
        @if($notifications->count() !== 0)
            <div class="infinite-scroll">
                @foreach ($notifications as $notification)
                    <div class="card mb-2 py-2 pb-0">
                        <div class="card-body p-2 px-3">
                            <h6>
                                <i class="fas fa-bell me-2 {{ is_null($notification->read_at) ? 'text-warning' : 'text-primary' }}"></i>
                                {{ Str::limit($notification->data['title'], 35) }}
                            </h6>
                            <p class="mb-1">
                                {{ Str::limit($notification->data['message'], 100) }}
                            </p>
                            <p class="text-muted mb-3">
                                {{ Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i:s A') }}
                            </p>
                            <a href="{{ route('get-notification-details', $notification->id) }}" class="border-top border-muted py-2 d-flex justify-content-between">
                                <span class="fw-bold">Details</span>
                                <span class="me-3">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
                {{ $notifications->links() }}
            </div>
        @else
            <div class="card mb-2">
                <div class="card-body p-2 px-3">
                    <div class="text-center">
                        <img src="{{ asset('frontend/img/page-not-found.png') }}" alt="">
                        <p class="text-danger mt-3 fw-bold">
                            No Data Found on that filer!
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<div style="width:100%;height:0;padding-bottom:100%;position:relative;"><iframe src="https://giphy.com/embed/3o7bu3XilJ5BOiSGic" width="100%" height="100%" style="position:absolute" frameBorder="0" class="giphy-embed" allowFullScreen></iframe></div><p><a href="https://giphy.com/gifs/youtube-loading-gif-3o7bu3XilJ5BOiSGic">via GIPHY</a></p>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            })
        });
    </script>
@endpush