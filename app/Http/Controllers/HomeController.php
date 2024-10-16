<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id; 

        $firstWallet = Wallet::select('id', 'name', 'balance', 'icon_id') 
            ->with(['icon' => function($query) {
                $query->select('id', 'path'); 
            }])
            ->where('user_id', $user_id)
            ->first();

        $totalBalance = Wallet::where('user_id', $user_id)->sum('balance');
        
        if ($request->wantsJson()) {
            return response()->json([
                'wallet' => $firstWallet,
                'totalBalance' => $totalBalance,
            ]);
        }

        return Inertia::render('Home/Index', [
            'wallet' => $firstWallet,
            'totalBalance' => $totalBalance,
        ]);
    }
}