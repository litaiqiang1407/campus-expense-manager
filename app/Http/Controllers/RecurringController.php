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
        $user = $request->user();

        $data = $this->transactionRecurringService->getIndexRecurringTransaction($user->id);

        if ($request->wantsJson()) {
            return response()->json([
                'transactionsRecurring' => $data['transactionsRecurring'],
                //'wallets' => $data['wallets'],
            ]);
        }

        return Inertia::render('Recurring/Index', [
            'transactionsRecurring' => $data['transactionsRecurring'],
            //'wallets' => $data['wallets'],
        ]);
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
    public function transactionRecurringDetails(Request $request, $transactionRecurringId)
    {
        $user = $request->user();
        $transactionRecurring = $this->transactionRecurringService->getDetailsRecurringTransaction($transactionRecurringId);

        if ($request->wantsJson()) {
            // Trả về dữ liệu trực tiếp mà không cần bọc trong mảng
            return response()->json(data: [
                'transactionRecurring' => $transactionRecurring,
            ]);
        }

        return Inertia::render('Recurring/Details', [
            'transactionRecurring' => $transactionRecurring,
        ]);
    }

    public function delete(Request $request, $transactionId)
    {
        $user_id = $request->user()->id;
        $this->transactionRecurringService->deleteTransaction($transactionId, $user_id);

        return response()->json([
            'success' => true,
            'message' => 'transaction recurring deleted successfully!',
        ]);
    }
}
