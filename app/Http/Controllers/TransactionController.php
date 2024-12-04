<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $data = $this->transactionService->getTransactionsAndWalletsByUser($user->id);
        //dd($data);
        if ($request->wantsJson()) {
            return response()->json([
                'transactions' => $data['transactions'],
                'wallets' => $data['wallets'],
                'calculatedTransactions' => $data['calculatedTransactions']
            ]);
        }

        return Inertia::render('Transaction/Index', [
            'transactions' => $data['transactions'],
            'wallets' => $data['wallets'],
            'calculatedTransactions' => $data['calculatedTransactions']
        ]);
    }
    public function create(Request $request)
    {
        $categories = $this->transactionService->getCategories();
        $user = $request->user();
        $walletId = $request->input('walletId');
        $wallet = Wallet::with('icon')
            ->select('id', 'user_id', 'name')
            ->where('id', $walletId)
            ->where('user_id', $user->id)
            ->first();
        if ($request->wantsJson()) {
            return response()->json([
                'categories' => $categories,
                'wallet' => $wallet,
            ]);
        }

        return Inertia::render('Transaction/Create', [
            'categories' => $categories,
            'wallet' => $wallet,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'note' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $this->transactionService->createTransaction($validatedData, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
        ]);
    }

    public function edit(Request $request, $transactionId)
    {
        $user = $request->user();
        $categories = $this->transactionService->getCategories();
        $transaction = $this->transactionService->getTransactionEdit($transactionId);

        if ($request->wantsJson()) {
            return response()->json(data: [
                'transaction' => $transaction,
                'categories' => $categories,
            ]);
        }

        return Inertia::render('Transaction/Edit', [
            'transaction' => $transaction,
            'categories' => $categories,
        ]);
    }
    public function update(Request $request, $transactionId)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|',
            'type' => 'required|in:expense,income',
            'category_id' => 'required|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'note' => 'nullable|string',
            'date' => 'required|date|',
        ]);


        $transaction = $this->transactionService->updateTransaction($transactionId, $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully!',
            'transaction' => $transaction,
        ]);
    }
    public function transactionDetails(Request $request, $transactionId)
    {
        $user = $request->user();
        $transaction = $this->transactionService->getTransactionDetails($transactionId);

        if ($request->wantsJson()) {
            return response()->json(data: [
                'transaction' => $transaction,
            ]);
        }

        return Inertia::render('Transaction/TransactionDetails/Index', [
            'transaction' => $transaction,
        ]);
    }
    public function delete(Request $request, $transactionId)
    {
        $user_id = $request->user()->id;
        $this->transactionService->deleteTransaction($transactionId, $user_id);

        return response()->json([
            'success' => true,
            'message' => 'transaction deleted successfully!',
        ]);
    }
}
