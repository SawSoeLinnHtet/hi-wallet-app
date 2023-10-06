@extends('frontend.layouts.app')

@section('title', 'Transfer Confirmation')

@section('content')
    <div class="transfer">
        <div class="card">
            @include('frontend.layouts.flash')
            <form action="{{ route('post-wallet-transfer-complete') }}" method="POST" id="confirm_form">
                @csrf
                <input type="hidden" name="to_phone" value="{{ $to_account->phone }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="hidden" name="description" value="{{ $description }}">

                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="" class="mb-0 fw-bold">From</label>
                        <p class="mb-1 text-muted">{{ $from_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $from_account->phone }}</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="mb-0 fw-bold">To</label>
                        <p class="mb-1 text-muted">{{ $to_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }}</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="mb-0 fw-bold">Amount (MMK)</label>
                        <p class="mb-1 text-muted">{{ number_format($amount) }} MMK</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="" class="mb-0 fw-bold">Description</label>
                        <p class="mb-1 text-muted">{{ $description }}</p>
                    </div>

                    <button type="submit" class="btn btn-theme w-100 mt-4" id="confirm-btn">Confirm</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#confirm-btn').on('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Please fill your password!',
                    icon: 'info',
                    html:'<input type="password" name="password" class="form-control mt-2 text-center" id="password">',
                    showCloseButton: true,
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var password = $('#password').val();

                        $.ajax({
                            url: '/transfer/confirm/password/check?password=' + password,
                            type: 'GET',
                            success: function (res) {
                                if (res.status == 'success') {
                                    $('#confirm_form').submit();
                                }else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: res.message,
                                    })
                                }
                            }
                        })
                    }
                })
            })
        })
    </script>
@endpush