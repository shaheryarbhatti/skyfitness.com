@extends('layouts.app')

@section('content')
<style>
    #dateFilter {
    background-color: #fff !important;
    cursor: pointer;
    font-weight: 600;
    min-width: 140px;
    border-left: none;
}

.input-group-text {
    border-top-left-radius: 8px !important;
    border-bottom-left-radius: 8px !important;
}

.form-control {
    border-top-right-radius: 8px !important;
    border-bottom-right-radius: 8px !important;
}

/* Flatpickr Custom Styling to match your theme */
.flatpickr-day.selected {
    background: var(--theme-default, #7367f0) !important;
    border-color: var(--theme-default, #7367f0) !important;
}
</style>
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('attendance_logs') }}</h4>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-primary border-primary text-white">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" id="dateFilter" placeholder="Select Date.."
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive custom-scrollbar mb-4">
                            <table id="attendanceTable" class="display table table-hover table-bordered"
                                style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('member') }}</th>
                                        <th>{{ __('date') }}</th> {{-- This uses attendance_date --}}
                                        <th>{{ __('check_in') }}</th>
                                        <th>{{ __('check_out') }}</th>
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
    var table = $('#attendanceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("attendance.manage") }}',
            data: function(d) {
                d.date = $('#dateFilter').val();
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'member_name',
                name: 'member.full_name'
            },
            {
                data: 'attendance_date',
                name: 'created_at'
            }, // Data mapped from created_at
            {
                data: 'check_in',
                name: 'check_in'
            },
            {
                data: 'check_out',
                name: 'check_out'
            }
        ],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [
            [2, 'asc']
        ],
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        },
        responsive: true,
        dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>tip',
    });

    // Refresh table when date filter changes
    $('#dateFilter').on('change', function() {
        $('#attendanceTable').DataTable().draw();
    });
});
</script>

<script>
$(document).ready(function() {
    // Initialize Flatpickr
    const datePicker = flatpickr("#dateFilter", {
        defaultDate: "today",
        altInput: true,
        altFormat: "F j, Y", // More readable format: March 17, 2026
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
            // This triggers your DataTable refresh automatically
            $('#attendanceTable').DataTable().draw();
        }
    });
});
</script>
@endpush
