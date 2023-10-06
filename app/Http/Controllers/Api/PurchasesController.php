<?php

namespace App\Http\Controllers\Api;


use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class PurchasesController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Purchases list',
            'data' => Purchase::with('purchaseDetails.inventory')->get()
        ], 200);
    }

    public function show($purchases)
    {
        $purchases = Purchase::find($purchases)->with('purchaseDetails.inventory')->first();

        if($purchases) {
            return response()->json([
                'success' => true,
                'message' => 'Detail purchases',
                'data' => $purchases
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Purchases not found',
                'data' => ''
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'number' => 'required',
                'date' => 'required|date',
                'items' => 'required|array',
                'items.*.inventory_id' => 'required|numeric',
                'items.*.qty' => 'required|numeric',
            ]);

            DB::beginTransaction();

            $purchases = Purchase::create([
                'user_id' => auth()->user()->id,
                'number' => $request->number,
                'date' => $request->date,
            ]);

            foreach($request->items as $item) {
                $inventory = Inventory::find($item['inventory_id']);
                if($inventory) {
                    $inventory->stock = $inventory->stock + $item['qty'];
                    $inventory->save();
                } else {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Inventory not found',
                        'data' => ''
                    ], 404);
                }

                $purchases->purchaseDetails()->create([
                    'inventory_id' => $item['inventory_id'],
                    'quantity' => $item['qty'],
                    'price' => $inventory->price,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchases created',
                'data' => $purchases
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchases failed to create',
                'data' => $e->errors()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchases failed to create',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $purchases)
    {
        try {
            $this->validate($request, [
                'number' => 'required',
                'date' => 'required|date',
                'items' => 'required|array',
                'items.*.inventory_id' => 'required|numeric',
                'items.*.qty' => 'required|numeric',
            ]);

            DB::beginTransaction();

            $purchases = Purchase::find($purchases);

            if($purchases) {
                $purchases->update([
                    'number' => $request->number,
                    'date' => $request->date,
                ]);

                foreach($request->items as $item) {
                    $inventory = Inventory::find($item['inventory_id']);
                    if($inventory) {
                        $inventory->stock = $inventory->stock - $purchases->purchaseDetails->where('inventory_id', $item['inventory_id'])->first()->qty;
                        $inventory->stock = $inventory->stock + $item['qty'];
                        $inventory->save();
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Inventory not found',
                            'data' => ''
                        ], 404);
                    }

                    $purchases->purchaseDetails()->create([
                        'inventory_id' => $item['inventory_id'],
                        'quantity' => $item['qty'],
                        'price' => $inventory->price,
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Purchases updated',
                    'data' => $purchases
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Purchases not found',
                    'data' => ''
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchases failed to update',
                'data' => $e->errors()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchases failed to update',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($purchases)
    {
        try {
            $purchases = Purchase::find($purchases);

            if($purchases) {
                $purchases->purchaseDetails()->delete();
                $purchases->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Purchases deleted',
                    'data' => $purchases
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Purchases not found',
                    'data' => ''
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Purchases failed to delete',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
