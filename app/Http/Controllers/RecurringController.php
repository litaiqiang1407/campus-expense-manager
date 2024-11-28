<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TransactionRecurringService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RecurringController extends Controller
{
    protected $transactionRecurringService;

    public function __construct(TransactionRecurringService $transactionRecurringService)
    {
        $this->transactionRecurringService = $transactionRecurringService;
    }
    public function index(Request $request)
    {
        // $user = $request->user();

        // $data = $this->transactionRecurringService->getTransactionsAndWalletsByUser($user->id);

        // if ($request->wantsJson()) {
        //     return response()->json([
        //         'transactions' => $data['transactions'],
        //         'wallets' => $data['wallets'],
        //     ]);
        // }

        // return Inertia::render('Transaction/Index', [
        //     'transactions' => $data['transactions'],
        //     'wallets' => $data['wallets'],
        // ]);
    }
    public function create(Request $request)
    {
        // $categories = $this->transactionService->getCategories();
        // $user = $request->user();
        // $walletId = $request->input('walletId');
        // $wallet = Wallet::with('icon')
        //     ->select('id', 'user_id', 'name')
        //     ->where('id', $walletId)
        //     ->where('user_id', $user->id)
        //     ->first();
        // if ($request->wantsJson()) {
        //     return response()->json([
        //         'categories' => $categories,
        //         'wallet' => $wallet,
        //     ]);
        // }

        // return Inertia::render('Transaction/Create', [
        //     'categories' => $categories,
        //     'wallet' => $wallet,
        // ]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'wallet_id' => 'required|exists:wallets,id',
            'start_date' => 'required',
            'interval' => 'required',
            'type' => 'required',
            'frequency' => 'required',
            'note' => 'nullable|string|max:255',
        ]);

        $this->transactionRecurringService->createRecurringTransaction($validatedData, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
        ], 201);
    }
}
