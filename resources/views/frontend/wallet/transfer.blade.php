@extends('frontend.layouts.app')

@section('title', 'Transfer')

@section('content')
    <div class="transfer">
        <div class="card">
            @include('frontend.layouts.flash')
            <form action="{{ route('get-wallet-transfer-confirm') }}" id="transfer-form" method="GET" autocomplete="off">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="" class="mb-1">From</label>
                        <p class="mb-1 text-muted">{{ auth()->user()->name }}</p>
                        <p class="mb-1 text-muted">{{ auth()->user()->phone }}</p>
                    </div>

                    <input type="hidden" class="hash_value" id="hash_value" name="hash_value" value="">

                    @if(!isset($to_phone))
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
                    @else
                        <div class="form-group mb-3">
                            <label for="" class="mb-1">To</label>
                            <p class="mb-1 text-muted">{{ $to_phone->name }}</p>
                            <p class="mb-1 text-muted">{{ $to_phone->phone }}</p>
                        </div>

                        <input type="hidden" name="to_phone" id="to_phone" value="{{ $to_phone->phone }}">
                    @endif

                    <div class="form-group mb-2"> 
                        <label for="amount" class="mb-2">Amount (MMK) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}">
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <p class="mb-0 text-end mt-1">
                            <span class="text-success fw-bold">Your balance: </span>
                            <span class="text-muted">{{ number_format(auth()->user()->wallet->amount) }} MMK</span>
                        </p>
                    </div>

                    <div class="form-group mb-2">
                        <label for="description" class="mb-2">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-theme w-100 mt-4 submit-btn">Continue</button>
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

            $(document).on('click', '.submit-btn', function (e) {
                e.preventDefault()

                var to_phone = $('#to_phone').val();
                var amount = $('#amount').val();
                var description = $('#description').val();

                $.ajax({
                    url: `/transfer/hash?to_phone=${to_phone}&amount=${amount}&description=${description}`,
                    type: 'GET',
                    success: function (res) {
                        if (res.status == 'success') {
                            $('#hash_value').val(res.data);

                            $('#transfer-form').submit()
                        }
                    }
                })
            })
        })
    </script>
@endpush