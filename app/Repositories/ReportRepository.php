<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use InvalidArgumentException;

class ReportRepository
{
    public function getReports($userId, $startDate = null, $endDate = null, $walletId = null)
    {
        if (is_null($startDate) && is_null($endDate)) {
            $startDate = null; 
            $endDate = null; 
        } else {
            try {
                $startDate = Carbon::parse($startDate)->startOfDay()->toDateTimeString();
                $endDate = Carbon::parse($endDate)->endOfDay()->toDateTimeString();
            } catch (\Exception $e) {
                throw new InvalidArgumentException('Invalid startDate or endDate provided.');
            }
        }
    
        $query = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->leftJoin('icons as category_icons', 'categories.icon_id', '=', 'category_icons.id')
            ->select(
                'categories.name as category_name',
                'categories.type as category_type',
                'category_icons.path as category_icon_path',
                DB::raw('SUM(transactions.amount) as category_balance')
            )
            ->where('transactions.user_id', $userId)
            ->groupBy('categories.id');
    
        if ($startDate && $endDate) {
            $query->whereBetween('transactions.date', [$startDate, $endDate]);
        }
    
        if (!is_null($walletId)) {
            $query->where('transactions.wallet_id', $walletId);
        }
    
        $data = $query->get();
    
        $income = [];
        $expense = [];
        $totalIncome = 0;
        $totalExpense = 0;
    
        foreach ($data as $item) {
            $transaction = [
                'category' => $item->category_name,
                'icon' => $item->category_icon_path,
                'balance' => (float)$item->category_balance,
            ];
    
            if ($item->category_type === 'income') {
                $income[] = $transaction;
                $totalIncome += $transaction['balance'];
            } elseif ($item->category_type === 'expense') {
                $expense[] = $transaction;
                $totalExpense += $transaction['balance'];
            }
        }
    
        return [
            'income' => [
                'balance' => $totalIncome,
                'categories' => $income,
            ],
            'expense' => [
                'balance' => $totalExpense,
                'categories' => $expense,
            ],
        ];
    }         
    
    public function getCategoryReports($userId, $startDate = null, $endDate = null, $categoryId = null)
    {
        if (is_null($startDate) && is_null($endDate)) {
            $startDate = null;
            $endDate = null;
        } else {
            try {
                $startDate = Carbon::parse($startDate)->startOfDay()->toDateTimeString();
                $endDate = Carbon::parse($endDate)->endOfDay()->toDateTimeString();
            } catch (\Exception $e) {
                throw new InvalidArgumentException('Invalid startDate or endDate provided.');
            }
        }
    
        $query = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->leftJoin('icons as category_icons', 'categories.icon_id', '=', 'category_icons.id')
            ->select(
                'transactions.created_at as transaction_date',
                'categories.type as category_type',
                'category_icons.path as category_icon_path',
                DB::raw('SUM(transactions.amount) as total_amount')
            )
            ->where('transactions.user_id', $userId)
            ->groupBy('transactions.created_at', 'categories.type', 'category_icons.path'); 
    
        if ($startDate && $endDate) {
            $query->whereBetween('transactions.created_at', [$startDate, $endDate]);
        }
    
        if (!is_null($categoryId)) {
            $query->where('transactions.category_id', $categoryId);
        }
    
        $data = $query->get();
    
        $income = [];
        $expense = [];
        $totalIncome = 0;
        $totalExpense = 0;
    
        foreach ($data as $item) {
            $transaction = [
                'date' => $item->transaction_date,
                'icon' => $item->category_icon_path,
                'amount' => (float)$item->total_amount,
            ];
    
            if ($item->category_type === 'income') {
                $income[] = $transaction;
                $totalIncome += $transaction['amount'];
            } elseif ($item->category_type === 'expense') {
                $expense[] = $transaction;
                $totalExpense += $transaction['amount'];
            }
        }
    
        return [
            'income' => [
                'balance' => $totalIncome,
                'transactions' => $income,
            ],
            'expense' => [
                'balance' => $totalExpense,
                'transactions' => $expense,
            ],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
             
}