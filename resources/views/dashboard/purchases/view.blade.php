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
                    <li><a href="{{ route('purchases.index') }}" class="text-primary">{{
                            $parent
                            }}</a> /</li>
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
                    <h2 class="mb-2.5 font-semibold text-title-md3">Purchase Detail</h2>
                    <table class="mb-2.5">
                        <tr>
                            <td>Purchase By</td>
                            <td>{{ $purchases->user->name }}</td>
                        </tr>
                        <tr>
                            <td>Purchase Number</td>
                            <td>{{ $purchases->number }}</td>
                        </tr>
                        <tr>
                            <td>Purchase Date</td>
                            <td>{{ $purchases->date }}</td>
                        </tr>
                    </table>
                    <h2 class="mb-2.5 mt-2.5 font-semibold text-title-md3">Purchase Item List</h2>
                    <table class="mb-2.5">
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Item Price</th>
                            <th>Item Qty</th>
                            <th>Item Total</th>
                        </tr>
                        @foreach($purchases['purchaseDetails'] as $key => $purchaseDetail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $purchaseDetail['inventory']['name'] }}</td>
                            <td>{{ $purchaseDetail['inventory']['price']}}</td>
                            <td>{{ $purchaseDetail['qty'] }}</td>
                            <td>{{ "Rp " . number_format($purchaseDetail['inventory']['price'] * $purchaseDetail['qty'],
                                2,",",".") }}
                            </td>
                        </tr>
                        @endforeach
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
    });


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
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
@endpush