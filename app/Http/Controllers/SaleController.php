<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Sale;
use App\Models\Inventory;

class SaleController extends Controller
{
    public function __construct() {
        $this->middleware('role:superadmin|sales');
    }

    public function index() {
        $data = [
            'parent' => 'Sale',
            'title' => 'Sales List',
            'menu' => 'salesList'
        ];

        if (request()->ajax()) {
            if (session('role') == 'sales') {
                $inventories = Sale::with(['user', 'saleDetails'])->where('user_id', auth()->user()->id)->get();
            } else {
                $inventories = Sale::with(['user', 'saleDetails'])->get();
            }
            return DataTables::of($inventories)
                ->addColumn('user', function($rents) {
                    return $rents->user->name;
                })
                ->addColumn('total', function($rents) {
                    return $rents->saleDetails->sum(function($detail) {
                        return $detail->qty * $detail->price;
                    });
                })
                ->make();
        }

        return view('dashboard.sales.list', $data);
    }

    public function add() {
        $data = [
            'parent' => 'Sales',
            'title' => 'Add Sales',
            'menu' => 'salesAdd',
            'inventories' => Inventory::all()
        ];

        return view('dashboard.sales.add', $data);
    }

    public function view(Sale $sale) {
        $data = [
            'parent' => 'Sales',
            'title' => 'View Sale ' . $sale->number . ' Details',
            'menu' => 'salesList',
            'sales' => $sale->load(['user','saleDetails.inventory'])->where('id', $sale->id)->first()
        ];

        // dd($data['sales']['saleDetails'][0]['inventory']);

        return view('dashboard.sales.view', $data);
    }

    public function update(Sale $sale)
    {
        $data = [
            'parent' => 'Sales',
            'title' => 'Update Sale ' . $sale->number . ' Details',
            'menu' => 'salesUpdate',
            'sales' => $sale->load(['user','saleDetails.inventory'])->where('id', $sale->id)->first(),
            'inventories' => Inventory::all()
        ];

        return view('dashboard.sales.add', $data);
    }
}
