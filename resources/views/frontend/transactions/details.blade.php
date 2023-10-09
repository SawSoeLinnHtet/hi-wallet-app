@extends('frontend.layouts.app')

@section('title', 'Transaction Details')

@section('content')
    <div class="transaction-details">
        <div class="card">
            <div class="card-body p-2 px-3">
                <div class="text-center mb-3">
                    <img src="{{ asset('frontend/img/checked.png') }}" alt="">
                </div>
                
                @if (session('transfer_success'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ session('transfer_success') }}
                    </div>
                @endif

                <h6 class="text-center mb-4 fw-bolder {{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }}">
                    {{ number_format($transaction->amount) }} <small>MMK</small>
                </h6>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Trx ID:</p>
                    <p class="mb-0">{{ $transaction->trx_id }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Ref No:</p>
                    <p class="mb-0">{{ $transaction->ref_no }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Type:</p>
                    <p class="mb-0">
                        <span class="badge badge-pill {{ $transaction->type == 'income' ? 'income-color' : 'expense-color' }}">
                            {{ $transaction->type == 'income' ? 'Income' : 'Expense' }}
                        </span>
                    </p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Amount:</p>
                    <p class="mb-0">{{ number_format($transaction->amount) }} <small>MMK</small></p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Transaction Time:</p>
                    <p class="mb-0">{{ $transaction->created_at }}</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">
                        {{ $transaction->type == 'income' ? 'Transfer From:' : 'Transfer To:' }}
                    </p>
                    <p class="mb-0">
                        {{ $transaction->Source->name }} 
                        <small class="d-block text-muted">( {{ $transaction->Source->phone }} )</small> 
                    </p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">
                        Description
                    </p>
                    <p class="mb-0">
                        {{ $transaction->description }} 
                    </p>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection