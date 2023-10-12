@extends('backend.layouts.app')

@section('title', 'Admin User Management')

@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>
                    Add Amount
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @include('backend.layouts.flash')
                <form action="{{ route('admin.post.wallet.amount.add') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="user-select">User</label>
                        <select name="user_id" id="user-select" class="form-control">
                            @foreach ($users as $key => $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ( {{ $user->phone }} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="text" id="amount" class="form-control" name="amount">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="" class="form-control"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center">
                        <button class="btn btn-secondary mr-2 back-btn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#user-select').select2({
                theme: 'bootstrap4',
            });
        });
    </script>
@endpush