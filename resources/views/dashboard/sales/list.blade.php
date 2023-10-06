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
                    <li class="text-primary"><a href="{{ route('inventories.index') }}" class="text-primary">{{ $title
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
                                <th>Code</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
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
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<script type="text/javascript">
    $(document).ready(function () {
        var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

       var tbl_lsit = $('#tbl_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ url()->current() }}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price', render: function(data, type, full, meta){
                    return formatter.format(data);
                }},
                {data: 'stock', name: 'stock'},
                {data: null, name: 'action', orderable: false, searchable: false, render: function(data, type, full, meta){
                    $output = "<a href=\"{{ route('inventories.update') }}/" + full.id + "\" class=\"hover:text-primary\" title=\"Update Item\"><i class=\"fi fi-rr-edit\"></i></a>&nbsp;&nbsp;";
                    $output += "<a href=\"javascript:void(0)\" onClick=\"updateStock('" + full.id + "', '" + full.name + "', '" + full.stock + "')\" class=\"hover:text-primary\" title=\"Update Stock\"><i class=\"fi fi-rr-warehouse-alt\"></i></a>&nbsp;&nbsp;";
                    $output += "<a href=\"javascript:void(0)\" onClick=\"removeItem('" + full.id + "', '" + full.name + "')\" class=\"hover:text-primary\" title=\"Remove Item\"><i class=\"fi fi-rr-trash\"></i></a>&nbsp;&nbsp;";
                    return $output;
                }},
            ]
        });
    });

    function updateStock(id, name, stock) {
        Swal.fire({
            title: 'Input new stock for ' + name,
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            inputValue: stock,
            showCancelButton: true,
            confirmButtonText: 'Update Stock',
            showLoaderOnConfirm: true,
            preConfirm: (stock) => {
                $.ajax({
                    url: "{{ route('api.inventories.update.stock') }}/" + id,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        stock: stock,
                        _method: "PUT",
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
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
            },
            allowOutsideClick: () => !Swal.isLoading()
            });
    }

    function removeItem(id, name) {
        Swal.fire({
            title: 'Are you sure want to delete this item?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('api.inventories.destroy') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Item has been deleted!',
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
<style>
    .modal-table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    .modal-table td,
    .modal-table th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    .modal-table tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
@endpush