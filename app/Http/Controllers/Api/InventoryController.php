<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Inventory list',
            'data' => Inventory::all()
        ], 200);
    }

    public function show($inventory)
    {
        $inventory = Inventory::find($inventory);

        if($inventory) {
            return response()->json([
                'success' => true,
                'message' => 'Detail inventory',
                'data' => $inventory
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Inventory not found',
                'data' => ''
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'code' => 'required|unique:inventories',
                'name' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
            ]);
            
            $inventory = Inventory::create([
                'code' => $request->code,
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inventory created',
                'data' => $inventory
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory failed to create',
                'data' => $e->errors()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory failed to create',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $inventory)
    {
        try {
            $this->validate($request, [
                'code' => 'required|unique:inventories,code,'.$inventory,
                'name' => 'required',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
            ]);
            
            $inventory = Inventory::find($inventory);

            if($inventory) {
                $inventory->update([
                    'code' => $request->code,
                    'name' => $request->name,
                    'price' => $request->price,
                    'stock' => $request->stock,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Inventory updated',
                    'data' => $inventory
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory not found',
                    'data' => ''
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory failed to update',
                'data' => $e->errors()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory failed to update',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStock(Request $request, $inventory)
    {
        try {
            $this->validate($request, [
                'stock' => 'required|numeric',
            ]);
            
            $inventory = Inventory::find($inventory);

            if($inventory) {
                $inventory->update([
                    'stock' => $request->stock,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Inventory updated',
                    'data' => $inventory
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory not found',
                    'data' => ''
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory failed to update',
                'data' => $e->errors()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory failed to update',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($inventory)
    {
        $inventory = Inventory::find($inventory);

        if($inventory) {
            $inventory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inventory deleted',
                'data' => $inventory
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Inventory not found',
                'data' => ''
            ], 404);
        }
    }
}
