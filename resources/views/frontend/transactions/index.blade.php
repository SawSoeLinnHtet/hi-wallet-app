@extends('frontend.layouts.app')

@section('title', 'Transactions')

@section('content')
    <div class="transaction">
        <div class="card mb-4">
            <div class="card-body p-2">
                <h6><i class="fas fa-filter me-2"></i>Filter</h6>
                <div class="row">
                    <div class="col-6 pe-1">
                        <div class="input-group my-2">
                            <label class="input-group-text p-1 p-md-3" for="date-picker">Date</label>
                            <input type="text" id="date-picker" class="form-control" value="{{ request()->date}}" placeholder="All">
                        </div>
                    </div>
                    <div class="col-6 ps-1">
                        <div class="input-group my-2">
                            <label class="input-group-text p-1 p-md-3" for="type-select">Type</label>
                            <select class="form-select" id="type-select">
                                <option value="">All</option>
                                <option value="income" {{ request()->type == 'income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ request()->type == 'expense' ? 'selected' : '' }}>Expense</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($transactions->count() !== 0)
            <h6 class="ms-2"><i class="fas fa-exchange-alt me-2"></i>Transactions</h6>
            <div class="infinite-scroll">
                    @foreach ($transactions as $transaction)
                        <div class="card mb-2">
                            <div class="card-body p-2 px-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1">
                                        <small>Trx ID: </small> {{ $transaction->trx_id }}
                                    </h6>
                                    <p class="mb-1 {{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($transaction->amount) }} <small>MMK</small>
                                    </p>
                                </div>
                                <p class="mb-1 text-muted">
                                    {{ $transaction->type == 'income' ? 'From' : 'To' }} {{ $transaction->Source->name }}
                                </p>
                                <p class="mb-1 text-muted">
                                    {{ Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d H:i:s A') }}
                                </p>
                                <a href="{{ route('get-transaction-details', $transaction->trx_id) }}" class="border-top border-muted py-2 d-flex justify-content-between">
                                    <span class="fw-bold">Details</span>
                                    <span class="me-3">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    {{ $transactions->links() }}
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
            });

            $('#date-picker').daterangepicker({
                "singleDatePicker": true,
                "autoUpdateInput": false,
                "autoApply": false,
                "locale": {
                    "format": "YYYY-MM-DD",
                },
            });

            $('#date-picker').on('apply.daterangepicker', function(ev, picker) {
                $('#date-picker').val(picker.startDate.format('YYYY-MM-DD'))    
                filter();
            });

            $('#date-picker').on('cancel.daterangepicker', function(ev, picker) {
                $('#date-picker').val('')
                filter();
            });

            $('#type-select').change(function () {
                filter();
            })

            function filter(){
                var date = $('#date-picker').val()
                var type = $('#type-select').val()

                history.pushState(null, '', `?date=${date}&type=${type}`)
                window.location.reload()
            }
        });
    </script>
@endpush