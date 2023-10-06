@extends('frontend.layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="account">
        <div class="profile mb-3">
            <img src="https://ui-avatars.com/api/?background=5842e3&color=fff&name={{ Auth::user()->name }}" alt="profile">
        </div>
        <div class="card mb-3">
            <div class="card-body pe-0">
                <div class="d-flex justify-content-between">
                    <span>
                        User Name
                    </span>
                    <span class="me-3">
                        {{ Auth::user()->name }}
                    </span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>
                        Phone
                    </span>
                    <span class="me-3">
                        {{ Auth::user()->phone }}
                    </span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>
                        Email
                    </span>
                    <span class="me-3">
                        {{ Auth::user()->email }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body pe-0">
                <div class="point">
                    <a href="{{ route('get-update-password') }}" class="d-flex justify-content-between">
                        <span>
                            Update Password
                        </span>
                        <span class="me-3">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    </a>
                </div>
                <hr>
                <div class="point">
                    <a href="#" class="d-flex justify-content-between logout-btn">
                        <span>
                            Logout
                        </span>
                        <span class="me-3">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.logout-btn', function (e) {
                e.preventDefault();

                var id = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure, you want to logout?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Logout!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('logout') }}",
                            type: 'POST',
                            success: function (res) {   
                                window.location.replace("{{ route('login') }}")
                            }
                        })
                    }
                })
            });
        })
    </script>
@endpush