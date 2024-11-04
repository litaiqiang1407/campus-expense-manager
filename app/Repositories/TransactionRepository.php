<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionRepository
{
    public function getTransactionsByWallet($userId, $walletId)
    {
        return Transaction::where('user_id', $userId)
            ->where('wallet_id', $walletId)
            ->get(['id', 'category_id', 'amount', 'date']);
    }
    
    public function getTransactionsByUser($userId)
    {
        return Transaction::with(['category.icon'])
            ->select('id', 'amount', 'note', 'category_id', 'user_id', 'date', 'wallet_id')
            ->where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function createTransaction($data, $userId)
    {
        return Transaction::create(array_merge($data, ['user_id' => $userId]));
    }

    public function updateTransaction($transactionId, $data)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $transaction->update($data);
        return $transaction;
    }

    public function getTransactionById($transactionId)
    {
        return Transaction::findOrFail($transactionId);
    }

    public function getWalletsByUser($userId)
    {
        return Wallet::select(
            'wallets.id',
            'wallets.name',
            'wallets.balance',
            'icons.path as icon_path',
            'icons.name as icon_name'
        )
            ->where('user_id', $userId)
            ->join('icons', 'wallets.icon_id', '=', 'icons.id')
            ->get();
    }

    public function calculateTotalBalance($userId)
    {
        $totalIncome = Transaction::where('user_id', $userId)
            ->whereHas('category', function($query) {
                $query->where('type', 'income');
            })
            ->sum('amount');
    
        $totalExpense = Transaction::where('user_id', $userId)
            ->whereHas('category', function($query) {
                $query->where('type', 'expense');
            })
            ->sum('amount');

        return $totalIncome - $totalExpense;
    }
        

    public function getCategoryById($categoryId)
    {
        return Category::findOrFail($categoryId);
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function updateWalletBalance($walletId, $amount, $isIncome)
    {
        $wallet = Wallet::findOrFail($walletId);

        if ($isIncome) {
            $wallet->balance += $amount;
        } else {
            $wallet->balance -= $amount;
        }

        $wallet->save();
    }

    public function getRecentTransactions($userId)
    {
        $transactions = DB::table('transactions')
            ->select(
                'transactions.id',
                'transactions.amount',
                'transactions.date',  
                'categories.name as name',
                'categories.type as type',
                'category_icons.path as category_icon_path',
                'wallet_icons.path as wallet_icon_path'
            )
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->leftJoin('icons as category_icons', 'categories.icon_id', '=', 'category_icons.id')
            ->leftJoin('wallets', 'transactions.wallet_id', '=', 'wallets.id')
            ->leftJoin('icons as wallet_icons', 'wallets.icon_id', '=', 'wallet_icons.id')
            ->where('transactions.user_id', $userId)
            ->orderBy('transactions.date', 'desc')
            ->limit(3)
            ->get();
    
        foreach ($transactions as $transaction) {
            $transaction->date = Carbon::parse($transaction->date)->format('d M Y');
        }
    
        return $transactions;
    }

    public function getTopSpending($userId, $filter = 'month')
    {
        $now = Carbon::now();
        
        if ($filter === 'week') {
            $startDate = $now->startOfWeek()->toDateString();
            $endDate = $now->endOfWeek()->toDateString();
        } else {
            $startDate = $now->startOfMonth()->toDateString();
            $endDate = $now->endOfMonth()->toDateString();
        }       
    
        $topSpending = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->leftJoin('icons as category_icons', 'categories.icon_id', '=', 'category_icons.id')
            ->select(
                'categories.name as name',
                'categories.type as type',
                DB::raw('SUM(transactions.amount) as total_amount'),
                'category_icons.path as category_icon_path'
            )
            ->where('categories.type', 'expense')
            ->where('transactions.user_id', $userId)
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->groupBy('categories.id')
            ->orderByDesc('total_amount')
            ->limit(3)
            ->get();
    
        $totalAmount = $topSpending->sum('total_amount');
        $topSpending = $topSpending->map(function ($item) use ($totalAmount) {
            $item->percentage = $totalAmount ? round(($item->total_amount / $totalAmount) * 100, 2) : 0;
            return $item;
        });
    
        return $topSpending;
    }  

    public function getReportTrending($userId)
    {
        $now = Carbon::now();
        $startDate = $now->startOfMonth()->toDateString();
        $endDate = $now->endOfMonth()->toDateString();
    
        $reportTrending = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                DB::raw('DATE(transactions.date) as transaction_date'), 
                'categories.type as category_type',
                DB::raw('SUM(transactions.amount) as total_amount') 
            )
            ->where('transactions.user_id', $userId)
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(transactions.date)'), 'categories.type') 
            ->orderBy('transaction_date') 
            ->get();
    
            $daysInMonth = [];
            $dateAmountExpense = [];
            $dateAmountIncome = [];

            $currentMonthDays = range(1, $now->daysInMonth);
            foreach ($currentMonthDays as $day) {
                $formattedDate = Carbon::create($now->year, $now->month, $day)->format('d/m/Y');
                $daysInMonth[] = $formattedDate;
                $dateAmountExpense[$formattedDate] = 0; 
                $dateAmountIncome[$formattedDate] = 0; 
            }
            
            foreach ($reportTrending as $item) {
                $formattedDate = Carbon::parse($item->transaction_date)->format('d/m/Y');
                if ($item->category_type === 'expense') {
                    $dateAmountExpense[$formattedDate] = (float)$item->total_amount;
                } elseif ($item->category_type === 'income') {
                    $dateAmountIncome[$formattedDate] = (float)$item->total_amount;
                }
            }
        
            $result = [
                'dates' => $daysInMonth,
                'expense' => array_values($dateAmountExpense),
                'income' => array_values($dateAmountIncome),
            ];
    
        return $result;
    }

    public function getReportSpending($userId)
    {
        $now = Carbon::now();

        $startOfThisMonth = $now->startOfMonth()->toDateString();
        $endOfThisMonth = $now->endOfMonth()->toDateString();
    
        $startOfLastMonth = $now->subMonth()->startOfMonth()->toDateString();
        $endOfLastMonth = $now->endOfMonth()->toDateString(); 

        $startOfThisWeek = $now->startOfWeek()->toDateString();
        $endOfThisWeek = $now->endOfWeek()->toDateString();
    
        $startOfLastWeek = $now->subWeek()->startOfWeek()->toDateString();
        $endOfLastWeek = $now->endOfWeek()->toDateString(); 

        $lastMonthExpense = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('categories.type', 'expense')
            ->whereBetween('transactions.date', [$startOfLastMonth, $endOfLastMonth])
            ->sum('transactions.amount');
    
        $thisMonthExpense = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('categories.type', 'expense')
            ->whereBetween('transactions.date', [$startOfThisMonth, $endOfThisMonth])
            ->sum('transactions.amount');

        $lastWeekExpense = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('categories.type', 'expense')
            ->whereBetween('transactions.date', [$startOfLastWeek, $endOfLastWeek])
            ->sum('transactions.amount');

        $thisWeekExpense = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('categories.type', 'expense')
            ->whereBetween('transactions.date', [$startOfThisWeek, $endOfThisWeek])
            ->sum('transactions.amount');

        $result = [
            'monthExpense' => [(float)$lastMonthExpense, (float)$thisMonthExpense],
            'weekExpense' => [(float)$lastWeekExpense, (float)$thisWeekExpense],
        ];
    
        return $result;
    }
      
}
