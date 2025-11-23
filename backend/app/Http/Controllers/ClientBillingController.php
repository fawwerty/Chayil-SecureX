<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;

class ClientBillingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $billing = Billing::where('client_id', $user->id)->get();
        return response()->json($billing);
    }
}
