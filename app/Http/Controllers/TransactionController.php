<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
        $transactions = Transaction::with(['category.icon', 'wallet'])
            ->select('id', 'amount', 'type', 'note', 'category_id', 'wallet_id', 'user_id', 'created_at')
            ->orderBy('created_at', 'desc');

        $data = $transactions->paginate(10)->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'note' => $transaction->note,
                'iconName' => optional($transaction->category->icon)->name,
                'iconPath' => optional($transaction->category->icon)->path,
                'walletName' => optional($transaction->wallet)->name,
                'walletBalance' => optional($transaction->wallet)->balance,
                'created_at' => $transaction->created_at->toDateTimeString(),
            ];
        });

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render('Transaction/Index', [
            'transactions' => $data,
        ]);
    }

    // Hiển thị form tạo giao dịch mới
    public function create(Request $request)
    {
        //$categories = $this->transactionService->getCategories(); // Giả sử bạn có phương thức này trong TransactionService
        //$wallets = $this->transactionService->getWallets(); // Giả sử bạn có phương thức này trong TransactionService

        if ($request->wantsJson()) {
            return response()->json([
                // 'categories' => $categories,
                // 'wallets' => $wallets,
            ]);
        }

        return Inertia::render('Transaction/Create', [
            // 'categories' => $categories,
            // 'wallets' => $wallets,
        ]);
    }

    // Lưu giao dịch mới
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:expense,income',
            'category_id' => 'required|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'note' => 'nullable|string',
        ]);

        $transaction = $this->transactionService->createTransaction($validatedData, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
            'transaction' => $transaction,
        ]);
    }

    // Hiển thị form chỉnh sửa giao dịch
    public function edit(Request $request, $transactionId)
    {
        $transaction = $this->transactionService->getTransactionById($transactionId);
        // $categories = $this->transactionService->getCategories(); // Giả sử bạn có phương thức này
        // $wallets = $this->transactionService->getWallets(); // Giả sử bạn có phương thức này

        if ($request->wantsJson()) {
            return response()->json([
                'transaction' => $transaction,
                // 'categories' => $categories,
                // 'wallets' => $wallets,
            ]);
        }

        return Inertia::render('Transaction/Edit', [
            'transaction' => $transaction,
            // 'categories' => $categories,
            // 'wallets' => $wallets,
        ]);
    }

    // Cập nhật giao dịch
    public function update(Request $request, $transactionId)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:expense,income',
            'category_id' => 'required|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'note' => 'nullable|string',
        ]);

        $transaction = $this->transactionService->updateTransaction($transactionId, $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully!',
            'transaction' => $transaction,
        ]);
    }
}
