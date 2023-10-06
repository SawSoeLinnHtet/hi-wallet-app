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

    <div>
        <div class="py-1 mb-3">
            <a href="{{ route('admin.admin-user.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add Admin User
            </a>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>    
                            <th>
                                No
                            </th>
                            <th>
                                Name
                            </th>
                            <th class="no-sort">
                                Email
                            </th>
                            <th>
                                Phone
                            </th>
                            <th>
                                IP
                            </th>
                            <th class="no-sort">
                                User Agent
                            </th>
                            <th>
                                Created At
                            </th>
                            <th>
                                Updated At
                            </th>
                            <th class="no-sort">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                @push('script')
                    <script>
                        $(document).ready(function () {
                            var table = $('#datatable').DataTable({
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
                                    },
                                    {
                                        data: 'ip',
                                        name: 'ip'
                                    },
                                    {
                                        data: 'user_agent',
                                        name: 'user_agent'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                                    {
                                        data: 'updated_at',
                                        name: 'updated_at'
                                    },
                                    {
                                        data: 'action',
                                        name: 'action'
                                    }
                                ],
                                order: [
                                    [7, 'desc']
                                ],
                                columnDefs: [{
                                    target: 'no-sort',
                                    sortable: false
                                }]
                            });

                            $(document).on('click', '.delete-btn', function (e) {
                                e.preventDefault();

                                var id = $(this).data('id');
                                
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "You won't be able to revert this!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, delete it!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: '/admin/admin-user/' + id,
                                            type: 'DELETE',
                                            success: function () {
                                                Swal.fire(
                                                    'Deleted!',
                                                    'Your file has been deleted.',
                                                    'success'
                                                )
                                                table.ajax.reload()
                                            }
                                        })
                                    }
                                })
                            });
                        })
                    </script>
                @endpush
            </div>
        </div>
    </div>

@endsection