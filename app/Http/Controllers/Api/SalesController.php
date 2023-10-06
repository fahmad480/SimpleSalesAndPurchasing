<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use App\Models\Inventory;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Sales list',
            'data' => Sale::with('saleDetails.inventory')->get()
        ], 200);
}

    public function show($sales)
    {
        $sales = Sale::find($sales)->with('saleDetails.inventory')->first();

        if($sales) {
            return response()->json([
                'success' => true,
                'message' => 'Detail sales',
                'data' => $sales
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sales not found',
                'data' => ''
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $items = json_decode($request->input('items'), true);
            $request->merge(['items' => $items]);

            $this->validate($request, [
                'number' => 'required',
                'date' => 'required|date',
                'items' => 'required|array',
                'items.*.inventory_id' => 'required|numeric',
                'items.*.qty' => 'required|numeric',
            ]);

            DB::beginTransaction();
            
            $sales = Sale::create([
                'user_id' => auth()->user()->id,
                'number' => $request->number,
                'date' => $request->date,
            ]);

            foreach($request->items as $item) {
                $inventory = Inventory::find($item['inventory_id']);
                $inventory->stock = $inventory->stock - $item['qty'];
                $inventory->save();

                $item = SaleDetail::create([
                    'sale_id' => $sales->id,
                    'inventory_id' => $item['inventory_id'],
                    'qty' => $item['qty'],
                    'price' => $inventory->price * $item['qty'],
                ]);

                $sales->saleDetails()->save($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sales created',
                'data' => $sales
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sales failed to create',
                'data' => $e->errors()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Sales failed to create',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $sales)
    {
        try {
            $items = json_decode($request->input('items'), true);
            $request->merge(['items' => $items]);

            $this->validate($request, [
                'number' => 'required',
                'date' => 'required|date',
                'items' => 'required|array',
                'items.*.inventory_id' => 'required|numeric',
                'items.*.qty' => 'required|numeric',
            ]);

            DB::beginTransaction();
            
            $sales = Sale::find($sales);

            if($sales) {
                $sales->update([
                    'user_id' => auth()->user()->id,
                    'number' => $request->number,
                    'date' => $request->date,
                ]);

                foreach($request->items as $item) {
                    $inventory = Inventory::find($item['inventory_id']);
                    if($inventory) {
                        $inventory->stock = $inventory->stock + $sales->saleDetails->where('inventory_id', $item['inventory_id'])->first()->qty;
                        $inventory->stock = $inventory->stock - $item['qty'];
                        $inventory->save();
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Inventory not found',
                            'data' => ''
                        ], 404);
                    }

                    $item = SaleDetail::create([
                        'sale_id' => $sales->id,
                        'inventory_id' => $item['inventory_id'],
                        'qty' => $item['qty'],
                        'price' => $inventory->price * $item['qty'],
                    ]);

                    $sales->saleDetails()->save($item);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Sales updated',
                    'data' => $sales
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales not found',
                    'data' => ''
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sales failed to update',
                'data' => $e->errors()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Sales failed to update',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($sales)
    {
        try {
            $sales = Sale::find($sales);

            if($sales) {
                $sales->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Sales deleted',
                    'data' => $sales
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sales not found',
                    'data' => ''
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sales failed to delete',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
