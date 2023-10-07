@extends('dashboard._layouts._app')

@section('content')
<main>
    <div class="max-w-screen-2xl mx-auto p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h2 class="font-semibold text-title-md2 text-black dark:text-white">{{ $title }}</h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('dashboard') }}">Dashboard /</a></li>
                    <li>{{ $parent }} /</li>
                    <li class="text-primary"><a href="{{ route(Route::current()->getName()) }}" class="text-primary">{{
                            $title
                            }}</a></li>
                </ol>
            </nav>
        </div>
        <!-- Breadcrumb End -->

        <!-- ====== Table Section Start -->
        <div class="flex flex-col gap-10">
            <!-- ====== Table One Start -->
            <div
                class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">

                <div class="flex flex-col">
                    <table id="tbl_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Input By</th>
                                <th>Number</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ====== Table One End -->
        </div>
        <!-- ====== Table Section End -->
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script
    src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.js">
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

       $('#tbl_list').DataTable({
            processing: true,
            serverSide: true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'csv', 'print'
            ],
            ajax: '{{ url()->current() }}',
            columns: [
                {data: null, name: 'id', "render": function ( data, type, full, meta ) {
                    return  meta.row + 1;
                }},
                {data: 'user', name: 'user'},
                {data: 'number', name: 'number'},
                {data: 'total', name: 'total', render: function(data, type, full, meta){
                    return formatter.format(data);
                }},
                {data: 'date', name: 'date'},
                {data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, full, meta){
                    $output = "<a href=\"{{ route('sales.view') }}/" + full.id + "\" class=\"hover:text-primary\" title=\"View Sales\"><i class=\"fi fi-rr-eye\"></i></a>&nbsp;&nbsp;";
                    $output += "<a href=\"{{ route('sales.update') }}/" + full.id + "\" class=\"hover:text-primary\" title=\"Update Sales\"><i class=\"fi fi-rr-edit\"></i></a>&nbsp;&nbsp;";
                    $output += "<a href=\"javascript:void(0)\" onClick=\"removeItem('" + full.id + "', '" + full.name + "')\" class=\"hover:text-primary\" title=\"Remove Sales\"><i class=\"fi fi-rr-trash\"></i></a>&nbsp;&nbsp;";
                    return $output;
                }},
            ]
        });
    });

    function removeItem(id, name) {
        Swal.fire({
            title: 'Are you sure want to delete this sales?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('api.sales.destroy') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Sales has been deleted!',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                        }).then(function() {
                            $('#tbl_list').DataTable().ajax.reload();
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong!',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                        });
                    }
                });
            }
        });
    }

</script>
@endpush

@push('styles')
<link
    href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.css"
    rel="stylesheet">
<style>
    /* Custom styling for search input */
    .dataTables_wrapper .dataTables_filter input {
        width: 300px;
        /* Sesuaikan lebar input sesuai kebutuhan Anda */
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    /* Custom styling for select input (entries per page) */
    .dataTables_wrapper .dataTables_length select {
        width: 100px;
        /* Sesuaikan lebar input sesuai kebutuhan Anda */
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }
</style>
@endpush