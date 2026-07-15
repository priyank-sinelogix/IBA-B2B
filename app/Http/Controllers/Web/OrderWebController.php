<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderWebController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('company_id', $request->user()->company_id)
            ->latest()->paginate(15);

        return view('orders.index', compact('orders'));
    }
}
