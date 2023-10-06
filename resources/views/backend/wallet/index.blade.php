@extends('backend.layouts.app')

@section('title', 'Wallet')

@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>
                    Wallets
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>    
                            <th>
                                No
                            </th>
                            <th>
                                Account Number
                            </th>
                            <th>
                                Account Person
                            </th>
                            <th class="no-sort">
                                Amount ( MMK )
                            </th>
                            <th class="no-sort">
                                Created At
                            </th>
                            <th class="no-sort">
                                Updated At
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
                                ajax: "/admin/wallet/datatable/ssd",
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
                                        data: 'account_number',
                                        name: 'account_number'
                                    },
                                    {
                                        data: 'account_person',
                                        name: 'account_person'
                                    },
                                    {
                                        data: 'amount',
                                        name: 'amount'
                                    },
                                    {
                                        data: 'created_at',
                                        name: 'created_at'
                                    },
                                    {
                                        data: 'updated_at',
                                        name: 'updated_at'
                                    },
                                ],
                                order: [
                                    [5, 'desc']
                                ],
                                columnDefs: [{
                                    target: 'no-sort',
                                    sortable: false
                                }]
                            });
                        })
                    </script>
                @endpush
            </div>
        </div>
    </div>

@endsection