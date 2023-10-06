<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function __construct() {
        $this->middleware('role:superadmin');
    }

    public function index() {
        $data = [
            'parent' => 'Inventory',
            'title' => 'Inventories List',
            'menu' => 'inventoriesList'
        ];

        if (request()->ajax()) {
            $inventories = Inventory::query();
            return DataTables::of($inventories)
                ->make();
        }

        return view('dashboard.inventory.list', $data);
    }

    public function add() {
        $data = [
            'parent' => 'Inventory',
            'title' => 'Add Inventory',
            'menu' => 'inventoriesAdd'
        ];

        return view('dashboard.inventory.add', $data);
    }

    public function update(Inventory $inventory)  {
        $data = [
            'parent' => 'Inventory',
            'title' => 'Update Inventory',
            'menu' => 'inventoriesUpdate',
            'inventory' => $inventory
        ];

        return view('dashboard.inventory.add', $data);
    }
}
