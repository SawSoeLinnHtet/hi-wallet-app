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
                    Admin User
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>    
                            <th>
                                No
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Phone
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                @push('script')
                    <script>
                        $(document).ready(function () {
                            $('#datatable').DataTable({
                                processing: true,
                                serverside: true,
                                ajax: "/admin/admin-user/datatable/ssd",
                                columns: [
                                    {
                                        data: 'id',
                                        name: 'id',
                                        render: function (data, type, row, meta) {
                                            var x = meta.row + 1;
                                            return x;
                                        }
                                    },
                                    {
                                        data: 'name',
                                        name: 'name'
                                    },
                                    {
                                        data: 'email',
                                        name: 'email'
                                    },
                                    {
                                        data: 'phone',
                                        name: 'phone'
                                    }
                                ]
                            });
                        })
                    </script>
                @endpush
            </div>
        </div>
    </div>

@endsection