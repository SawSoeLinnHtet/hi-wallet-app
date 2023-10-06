@extends('frontend.layouts.app')

@section('title', 'Transfer')

@section('content')
    <div class="transfer">
        <div class="card">
            <form action="{{ route('post-wallet-transfer-confirm') }}" method="GET" autocomplete="off">
                <div class="card-body">
                    <div class="form-group mb-2">
                        <label for="" class="mb-1">From</label>
                        <p class="mb-1 text-muted">{{ auth()->user()->name }}</p>
                        <p class="mb-1 text-muted">{{ auth()->user()->phone }}</p>
                    </div>

                    <div class="form-group mb-2">
                        <label for="" class="mb-2">To <span class="text-danger">* </span><span class="text-success" id="to_account_info"></span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="to_phone" id="to_phone" value="{{ old('to_phone') }}">
                            <span class="input-group-text check-account point px-4">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                        @error('to_phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-2"> 
                        <label for="" class="mb-2">Amount (MMK) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" value="{{ old('amount') }}">
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <p class="mb-0 text-end mt-1">
                            <span class="text-success fw-bold">Your balance: </span>
                            <span class="text-muted">{{ number_format(auth()->user()->wallet->amount) }} MMK</span>
                        </p>
                    </div>

                    <div class="form-group mb-2">
                        <label for="" class="mb-2">Description</label>
                        <textarea name="description" id="" class="form-control">{{ old('amount') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-theme w-100 mt-4">Continue</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready( function() {
            $(document).on('click', '.check-account', function () {
                var to_phone = $('#to_phone').val();

                $.ajax({
                    url: '/check_account?phone=' + to_phone,
                    type: 'GET',
                    success: function (res) {
                        if (res.status == 'success') {
                            $('#to_account_info').text('('+' '+res.data['name']+' '+')');
                        }else {
                            $('#to_account_info').text('('+' '+res.message+' '+')').addClass('text-danger');
                        }
                    }
                })
            })
        })
    </script>
@endpush