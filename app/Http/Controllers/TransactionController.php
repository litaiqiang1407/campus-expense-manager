<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Lấy ID người dùng từ yêu cầu (nếu cần)
        // $userId = $request->user()->id;

        // Lấy tất cả giao dịch của người dùng theo thứ tự thời gian mới nhất
        $transactions = Transaction::with(['category.icon', 'wallet', 'user'])
            ->select('id', 'amount', 'type', 'note', 'category_id', 'wallet_id', 'user_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Chuyển đổi giao dịch thành mảng với dữ liệu cần thiết
        $data = $transactions->map(function($transaction) {
            return [
                'id' => $transaction->id,
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'note' => $transaction->note,
                'iconName' => $transaction->category->icon->name ?? 'No Icon',
                'iconPath' => $transaction->category->icon->path ?? '',
                'created_at' => $transaction->created_at->toDateTimeString(),
            ];
        });
        // return Inertia::render('Transaction/Index', [
        // ]);
        return response()->json($transactions);
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
