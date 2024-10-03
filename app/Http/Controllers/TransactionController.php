<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Icon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('category', 'wallet', 'user')->get();

        $icon = Icon::first();

        $totalFlow = $transactions->sum('amount');
        $balance = '$' . $totalFlow;

        return view('transactions.index', compact('transactions', 'icon', 'totalFlow', 'balance'));
    }
}
