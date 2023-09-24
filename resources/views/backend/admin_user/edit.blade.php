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
                    Edit Admin User
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @include('backend.layouts.flash ')
                <form action="{{ route('admin.admin-user.update', $admin_user->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $admin_user->name ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input autocomplete="off" type="email" class="form-control" id="email" name="email" value="{{ $admin_user->email ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="number" class="form-control" id="phone" name="phone" value="{{ $admin_user->phone ?? '' }}">
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <button class="btn btn-secondary mr-2 back-btn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        {!! JsValidator::formRequest('App\Http\Requests\AdminUserRequest') !!}
    @endpush

@endsection
