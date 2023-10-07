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
                    <form id="form">
                        <div class="p-6.5">

                            <div class="mb-4.5">
                                <label class="mb-2.5 block text-black dark:text-white">
                                    Purchases Number <span class="text-meta-1">*</span>
                                </label>
                                <input type="text" placeholder="Input Purchases Number" id="number" name="number"
                                    value="{{ $menu === "purchasesUpdate" ? $purchases->number : "" }}" required
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium
                                outline-none transition focus:border-primary active:border-primary
                                disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark
                                dark:bg-form-input dark:focus:border-primary">
                            </div>

                            <div class="mb-4.5">
                                <label class="block text-black dark:text-white">
                                    Purchases Date <span class="text-meta-1">*</span>
                                </label>
                                <div class="mt-2.5 relative">
                                    <input type="date" id="date" name="date" required value="{{ $menu === "purchasesUpdate"
                                        ? $purchases->date : "" }}"
                                    class="custom-input-date custom-input-date-1 w-full rounded border-[1.5px]
                                    border-stroke bg-transparent py-3 px-5 font-medium outline-none transition
                                    focus:border-primary active:border-primary dark:border-form-strokedark
                                    dark:bg-form-input dark:focus:border-primary">
                                </div>
                            </div>

                            <div class="mb-4.5">
                                <label class="block text-black dark:text-white">
                                    Item List <span class="text-meta-1">*</span>
                                </label>
                                <a href="javascript:void(0)"
                                    class="flex w-full justify-center rounded bg-success p-2 font-medium text-gray"
                                    id="addnewitem">
                                    <i class="fi fi-rr-square-plus"></i>&nbsp;Add New Item
                                </a>
                                <table class="mt-2.5">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Item Qty</th>
                                            <th>Item Price</th>
                                            <th>Item Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items">
                                        @if ($menu === "purchasesUpdate")
                                        <tr id="items_sample">
                                            <td>
                                                <select name="items[][inventory_id]" id="items[inventory_id]"
                                                    onchange="getPrice(this)"
                                                    class="item_box w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                                    <option value="" disabled selected>Select Item</option>
                                                    @foreach($inventories as $inventory)
                                                    <option value="{{ $inventory->id }}"
                                                        data-price="{{ $inventory->price }}">{{ $inventory->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="items[][qty]" id="items[qty]"
                                                    onchange="getTotal(this)"
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                            </td>
                                            <td>
                                                <input type="number" id="items[price]" readonly
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                            </td>
                                            <td>
                                                <input type="number" id="items[total]" readonly
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                            </td>
                                            <td>
                                                <center><a href="javascript:void(0)" onClick="removeItem(this)"
                                                        class="hover:text-primary" title="Remove Item"><i
                                                            class="fi fi-rr-trash"></i></a></center>
                                            </td>
                                        </tr>
                                        @foreach($purchases['purchaseDetails'] as $key => $purchaseDetail)
                                        <tr>
                                            <td>
                                                <select name="items[][inventory_id]" id="items[inventory_id]"
                                                    onchange="getPrice(this)"
                                                    class="item_box w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                                    <option value="" disabled selected>Select Item</option>
                                                    @foreach($inventories as $inventory)
                                                    <option value="{{ $inventory->id }}" {{
                                                        $purchaseDetail['inventory_id']===$inventory->id ? "selected" : ""
                                                        }}
                                                        data-price="{{ $inventory->price }}">{{ $inventory->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="items[][qty]" id="items[qty]"
                                                    onchange="getTotal(this)"
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                                    value="{{ $purchaseDetail['qty'] }}">
                                            </td>
                                            <td>
                                                <input type="number" id="items[price]" readonly
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                                    value="{{ $purchaseDetail['inventory']['price'] }}">
                                            </td>
                                            <td>
                                                <input type="number" id="items[total]" readonly
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                                    value="{{ "
                                                    Rp " . number_format($purchaseDetail['inventory']['price'] * $purchaseDetail['qty'], 2, "
                                                    ,", "." ) }}">
                                            </td>
                                            <td>
                                                <center><a href="javascript:void(0)" onClick="removeItem(this)"
                                                        class="hover:text-primary" title="Remove Item"><i
                                                            class="fi fi-rr-trash"></i></a></center>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr id="items_sample">
                                            <td>
                                                <select name="items[][inventory_id]" id="items[inventory_id]"
                                                    onchange="getPrice(this)"
                                                    class="item_box w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                                    <option value="" disabled selected>Select Item</option>
                                                    @foreach($inventories as $inventory)
                                                    <option value="{{ $inventory->id }}"
                                                        data-price="{{ $inventory->price }}">{{ $inventory->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="items[][qty]" id="items[qty]"
                                                    onchange="getTotal(this)"
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                            </td>
                                            <td>
                                                <input type="number" id="items[price]" readonly
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                            </td>
                                            <td>
                                                <input type="number" id="items[total]" readonly
                                                    class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                            </td>
                                            <td>
                                                <center><a href="javascript:void(0)" onClick="removeItem(this)"
                                                        class="hover:text-primary" title="Remove Item"><i
                                                            class="fi fi-rr-trash"></i></a></center>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3"><b>Total</b></td>
                                            <td colspan="2"><b id="total">Rp 0,00</b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <button type="button"
                                class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray"
                                id="addnewpurchases">
                                {{ $menu === "purchasesUpdate" ? "Update Purchases" : "Add Purchases" }}
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
    var items_sample = null;

    $(document).ready(function () {
        items_sample = $("#items_sample").html();
        // $('.item_box').select2();

        @if ($menu === "purchasesUpdate")
        $("#items_sample").remove();
        $("#items tr").each(function() {
            var price = $(this).find("input[id='items[price]']").val();
            var qty = $(this).find("input[id='items[qty]']").val();
            var total = price * qty;
            $(this).find("input[id='items[total]']").val(total);
        });
        var total = 0;
        $("#items tr").each(function() {
            var item_total = $(this).find("input[id='items[total]']").val();
            total += parseInt(item_total);
        });
        $("#total").html(formatter.format(total));
        @endif
    });

    $("#addnewitem").click(function() {
        $("#items").append("<tr>" + items_sample + "</tr>");
    });

    function removeItem(e) {
        $(e).closest('tr').remove();
    }

    function getPrice(e) {
        var price = $(e).find(':selected').data('price');
        $(e).closest('tr').find("input[id='items[price]']").val(price);
        getTotal(e);
    }

    function getTotal(e) {
        var price = $(e).closest('tr').find("input[id='items[price]']").val();
        var qty = $(e).closest('tr').find("input[id='items[qty]']").val();
        var total = price * qty;
        $(e).closest('tr').find("input[id='items[total]']").val(total);
        var total = 0;
        $("#items tr").each(function() {
            var item_total = $(this).find("input[id='items[total]']").val();
            total += parseInt(item_total);
        });
        $("#total").html(formatter.format(total));
    }

    var formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    });

    $("#addnewpurchases").click(function(e) {
        e.preventDefault();
        // create array for items
        var items = [];
        $("#items tr").each(function() {
            var item = {
                inventory_id: $(this).find("select[name='items[][inventory_id]']").val(),
                qty: $(this).find("input[name='items[][qty]']").val(),
            };
            items.push(item);
        });

        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('number', $("#number").val());
        formData.append('date', $("#date").val());
        formData.append('items', JSON.stringify(items));
        formData.append('_method', '{{ $menu === "purchasesUpdate" ? "PUT" : "POST" }}');

        $.ajax({
            url: '{{ $menu === "purchasesUpdate" ? route('api.purchases.update', $purchases->id) : route('api.purchases.store') }}',
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ $menu === "purchasesUpdate" ? "Purchases has been updated!" : "Purchases has been added!" }}',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                }).then(function() {
                    window.location.href = '{{ route('purchases.index') }}';
                });
            },
            error: function(response) {
                Swal.fire({
                    title: "Error!",
                    text: response.responseJSON.message,
                    icon: "error",
                    button: "OK",
                });
            }
        });
    });
</script>
@endpush

@push('styles')
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
</style>
@endpush