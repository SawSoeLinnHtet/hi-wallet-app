@extends('frontend.layouts.app')

@section('title', 'Update Password')

@section('content')
    <div class="update-password">
        <div class="card mb-3">
            <div class="card-body pe-0">
                <div class="text-center">
                    <img src="{{ asset('frontend/img/update-password.png') }}" alt="update-password">
                </div>
                <form action="{{ route('post-update-password') }}" method="POST">
                    @csrf
                    <div class="form-group me-3 mb-3">
                        <label for="old-password" class="mb-2 fw-bold">Old Password</label>
                        <input type="password" name="old_password" id="old-password" class="form-control @error('old_password') is-invalid @enderror" value="{{ old('old_password') }}">
                        @error('old_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group me-3 mb-3">
                        <label for="new-password" class="mb-2 fw-bold">New Password</label>
                        <input type="password" name="new_password" id="new-password" class="form-control @error('new_password') is-invalid @enderror" value="{{ old('new_password') }}">
                        @error('new_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group me-3 pt-4">
                        <button type="submit" class="btn btn-theme w-100">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        {!! JsValidator::formRequest('App\Http\Requests\UpdatePasswordRequest') !!}
    @endpush
@endsection