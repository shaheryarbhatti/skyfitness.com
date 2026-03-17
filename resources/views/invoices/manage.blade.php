<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sky Fitness Gym – premium gym portal.">
    <meta name="author" content="skyfitnessgym.com">
    <title>{{ __('manage_invoices') }}</title>

    @extends('layouts.app')
    @section('content')

    <div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('manage_invoices') }}</h4>
                        <a href="{{ route('invoices.add') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i> {{ __('add_new_invoice') }}
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <div class="table-responsive custom-scrollbar mb-4">
                            <table id="invoicesTable" class="display table table-hover table-bordered" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('invoice_no') }}</th>
                                        <th>{{ __('member_name') }}</th>
                                        <th>{{ __('fee') }}</th>
                                        <th>{{ __('start_date') }}</th>
                                        <th>{{ __('end_date') }}</th>
                                        <th>{{ __('currency') }}</th>
                                        <th>{{ __('action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endsection

    @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
    /* Force spacing between filter/search and table */

    div.dataTables_wrapper div.dataTables_filter {
        position: absolute;
        right: 53px;
        top: 0px;
    }

    /* Base pagination container */
    .dataTables_paginate .pagination {
        margin: 0 auto !important;
        /* Center it */
        justify-content: center;
        gap: 0.25rem;
        /* Space between buttons */
        padding: 0.5rem 0;
    }

    /* Each page item/button */
    .dataTables_paginate .page-item .page-link {
        border-radius: 50% !important;
        /* Fully rounded like template demo */
        width: 38px;
        height: 38px;
        line-height: 38px;
        text-align: center;
        padding: 0;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        color: #495057;
        background-color: #fff;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    /* Hover effect (like template subtle hover) */
    .dataTables_paginate .page-item .page-link:hover {
        background-color: var(--theme-default, #7367f0) !important;
        color: white !important;
        border-color: var(--theme-default, #7367f0) !important;
        transform: scale(1.08);
    }

    /* Active / current page (blue background, white text) */
    .dataTables_paginate .page-item.active .page-link {
        background-color: var(--theme-default, #7367f0) !important;
        border-color: var(--theme-default, #7367f0) !important;
        color: white !important;
        box-shadow: 0 2px 6px rgba(115, 103, 240, 0.3);
        /* subtle shadow like premium templates */
        font-weight: bold;
    }

    /* Disabled buttons (Previous/Next when at end) */
    .dataTables_paginate .page-item.disabled .page-link {
        color: #adb5bd !important;
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        cursor: not-allowed;
    }

    /* First/Last/Previous/Next text buttons */
    .dataTables_paginate .page-item:first-child .page-link,
    .dataTables_paginate .page-item:last-child .page-link {
        border-radius: 0.375rem !important;
        /* Less round for arrows */
        width: auto;
        padding: 0 1rem;
    }

    /* Extra spacing below search bar & above table */
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 2.5rem !important;
    }

    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1.5rem !important;
    }

    /* Mobile adjustments */
    @media (max-width: 576px) {
        .dataTables_paginate .page-link {
            width: 32px;
            height: 32px;
            line-height: 32px;
            font-size: 0.875rem;
        }
    }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#invoicesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("invoices.manage") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'invoice_no', name: 'invoice_no', orderable: true },
                { data: 'member_name', name: 'member.full_name', orderable: true },
                { data: 'fee_display', name: 'fee', orderable: true },
                { data: 'start_date', name: 'start_date', orderable: true },
                { data: 'end_date', name: 'end_date', orderable: true },
                { data: 'currency', name: 'currency.name', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            responsive: true,
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search invoices...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ invoices",
                infoEmpty: "No invoices found",
                infoFiltered: "(filtered from _MAX_ total invoices)",
                zeroRecords: "No matching invoices found",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            },
            initComplete: function() {
                // Add search input styling
                $('.dataTables_filter input').addClass('form-control').css({
                    'width': '300px',
                    'display': 'inline-block',
                    'margin-left': '10px'
                });
            }
        });
    });
    </script>
    @endpush

