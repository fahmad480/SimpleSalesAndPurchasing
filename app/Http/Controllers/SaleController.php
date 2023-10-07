<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Sale;
use App\Models\Inventory;

class SaleController extends Controller
{
    public function index() {
        $data = [
            'parent' => 'Sale',
            'title' => 'Sales List',
            'menu' => 'salesList'
        ];

        if (request()->ajax()) {
            if (session('role') == 'sales') {
                $sales = Sale::with(['user', 'saleDetails.inventory'])->where('user_id', auth()->user()->id)->get();
            } else {
                $sales = Sale::with(['user', 'saleDetails.inventory'])->get();
            }
            return DataTables::of($sales)
                ->addColumn('user', function($e) {
                    return $e->user->name;
                })
                ->addColumn('total', function($e) {
                    return $e->saleDetails->sum(function($detail) {
                        return $detail->price;
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
