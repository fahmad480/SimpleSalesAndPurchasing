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
                            $title }}</a>
                    </li>
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
                    <form>
                        <div class="p-6.5">

                            <div class="mb-4.5">
                                <label class="mb-2.5 block text-black dark:text-white">
                                    Item Code <span class="text-meta-1">*</span>
                                </label>
                                <input type="text" placeholder="Input Item Code" id="code" name="code"
                                    value="{{ $menu === "inventoriesUpdate" ? $inventory->code : "" }}" required
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium
                                outline-none transition focus:border-primary active:border-primary
                                disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark
                                dark:bg-form-input dark:focus:border-primary">
                            </div>

                            <div class="mb-4.5">
                                <label class="mb-2.5 block text-black dark:text-white">
                                    Item Name <span class="text-meta-1">*</span>
                                </label>
                                <input type="text" placeholder="Input Item Name" id="name" name="name"
                                    value="{{ $menu === "inventoriesUpdate" ? $inventory->name : "" }}" required
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium
                                outline-none transition focus:border-primary active:border-primary
                                disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark
                                dark:bg-form-input dark:focus:border-primary">
                            </div>

                            <div class="mb-4.5">
                                <label class="mb-2.5 block text-black dark:text-white">
                                    Item Price <span class="text-meta-1">*</span>
                                </label>
                                <input type="number" placeholder="Input Item Price" id="price" name="price"
                                    value="{{ $menu === "inventoriesUpdate" ? $inventory->price : "" }}" required
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium
                                outline-none transition focus:border-primary active:border-primary
                                disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark
                                dark:bg-form-input dark:focus:border-primary">
                            </div>

                            <div class="mb-4.5">
                                <label class="mb-2.5 block text-black dark:text-white">
                                    Item Stock <span class="text-meta-1">*</span>
                                </label>
                                <input type="number" placeholder="Input Item Stock" id="stock" name="stock"
                                    value="{{ $menu === "inventoriesUpdate" ? $inventory->stock : "" }}" required
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium
                                outline-none transition focus:border-primary active:border-primary
                                disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark
                                dark:bg-form-input dark:focus:border-primary">
                            </div>

                            <div class="mb-4.5">
                                <label class="mb-2.5 block text-black dark:text-white">
                                    Total Price
                                </label>
                                <input type="text" placeholder="Total Price" id="total_price" readonly
                                    name="total_price" class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium
                                outline-none transition focus:border-primary active:border-primary
                                disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark
                                dark:bg-form-input dark:focus:border-primary">
                            </div>

                            <button class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray"
                                id="addnewitem">
                                {{ $menu === "rentUpdate" ? "Update Item" : "Add Item" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- ====== Table One End -->
        </div>
        <!-- ====== Table Section End -->
    </div>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        @if($menu === "inventoriesUpdate")
        calculate_estimated_price();
        @endif
    });
    var formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });

    $("#addnewitem").click(function(e) {
        e.preventDefault();
        var name = $("#name").val();
        var code = $("#code").val();
        var price = $("#price").val();
        var stock = $("#stock").val();

        $.ajax({
            url: "{{ $menu === 'inventoriesUpdate' ? route('api.inventories.update', $inventory->id) : route('api.inventories.store') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                code: code,
                name: name,
                price: price,
                stock: stock,
                _method: '{{ $menu === "inventoriesUpdate" ? "PUT" : "POST" }}'
            },
            success: function(response) {
                Swal.fire({
                    title: "Success!",
                    text: "{{ ($menu === 'inventoriesUpdate') ? 'Item updated successfully!' : 'New item has been added!' }}",
                    icon: "success",
                    button: "OK",
                }).then(function() {
                    window.location.href = "{{ route('inventories.index') }}";
                });
            },
            error: function(response) {
                Swal.fire({
                    title: "Error!",
                    text: "Something went wrong!",
                    icon: "error",
                    button: "OK",
                });
            }
        });
    });

    $("#price").change(function() {
        calculate_estimated_price();
    });

    $("#stock").change(function() {
        calculate_estimated_price();
    });

    function calculate_estimated_price() {
        var price = $("#price").val();
        var stock = $("#stock").val();

        var total_price = price * stock;


        $("#total_price").val(formatter.format(total_price));
    }
</script>
@endpush

@push('styles')
@endpush