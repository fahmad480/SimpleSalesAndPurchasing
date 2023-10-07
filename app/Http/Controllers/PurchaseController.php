<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Purchase;
use App\Models\Inventory;

class PurchaseController extends Controller
{
    public function __construct() {
        $this->middleware('role:superadmin|purchases');
    }

    public function index() {
        $data = [
            'parent' => 'Purchase',
            'title' => 'Purchases List',
            'menu' => 'purchasesList'
        ];

        if (request()->ajax()) {
            if (session('role') == 'purchases') {
                $purchases = Purchase::with(['user', 'purchaseDetails'])->where('user_id', auth()->user()->id)->get();
            } else {
                $purchases = Purchase::with(['user', 'purchaseDetails'])->get();
            }
            return DataTables::of($purchases)
                ->addColumn('user', function($e) {
                    return $e->user->name;
                })
                ->addColumn('total', function($e) {
                    return $e->purchaseDetails->sum(function($detail) {
                        return $detail->price;
                    });
                })
                ->make();
        }

        return view('dashboard.purchases.list', $data);
    }

    public function add() {
        $data = [
            'parent' => 'Purchases',
            'title' => 'Add Purchases',
            'menu' => 'purchasesAdd',
            'inventories' => Inventory::all()
        ];

        return view('dashboard.purchases.add', $data);
    }

    public function view(Purchase $purchase) {
        $data = [
            'parent' => 'Purchases',
            'title' => 'View Purchase ' . $purchase->number . ' Details',
            'menu' => 'purchasesList',
            'purchases' => $purchase->load(['user','purchaseDetails.inventory'])->where('id', $purchase->id)->first()
        ];

        return view('dashboard.purchases.view', $data);
    }

    public function update(Purchase $purchase)
    {
        $data = [
            'parent' => 'Purchases',
            'title' => 'Update Purchase ' . $purchase->number . ' Details',
            'menu' => 'purchasesUpdate',
            'purchases' => $purchase->load(['user','purchaseDetails.inventory'])->where('id', $purchase->id)->first(),
            'inventories' => Inventory::all()
        ];

        return view('dashboard.purchases.add', $data);
    }
}
