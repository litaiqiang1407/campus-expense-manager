<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
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

    public function markAsRead($id)
    {
        return response()->json(['message' => 'This method is not applicable for transactions'], 405);
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
