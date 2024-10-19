<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
    
        $walletId = $request->input('walletId');
    
        if ($walletId) {
            $wallet = Wallet::with('icon')->where('id', $walletId)->where('user_id', $userId)->first();
    
            if (!$wallet) {
                return response()->json(['budgets' => []]);
            }
    
            $budgets = Budget::with([
                'category' => function ($query) {
                    $query->select('id', 'name', 'icon_id');
                },
                'category.icon' => function ($query) {
                    $query->select('id', 'path'); 
                }
            ])
            ->where('user_id', $userId)
            ->where('wallet_id', $wallet->id) 
            ->get(['id', 'category_id', 'amount', 'time_range', 'created_at']);
    
        } else {
            $wallet = Wallet::where('user_id', $userId)
                ->where('name', Wallet::TOTAL_WALLET_NAME)
                ->first();
    
            if (!$wallet) {
                return response()->json(['budgets' => []]);
            }
    
            $budgets = Budget::with([
                'category' => function ($query) {
                    $query->select('id', 'name', 'icon_id');
                },
                'category.icon' => function ($query) {
                    $query->select('id', 'path'); 
                }
            ])
            ->where('user_id', $userId)
            ->where('wallet_id', $wallet->id) 
            ->get(['id', 'category_id', 'amount', 'time_range', 'created_at']);
        }
    
        $walletData = [
            'id' => $wallet->id,
            'icon_path' => $wallet->icon->path ?? null,
        ];
    
        $budgetsData = $budgets->map(function ($budget) {
            return [
                'id' => $budget->id,
                'amount' => $budget->amount,
                'time_range' => $budget->time_range,
                'created_at' => $budget->created_at,
                'category' => [
                    'id' => $budget->category->id,
                    'name' => $budget->category->name,
                    'icon_path' => $budget->category->icon->path ?? null, 
                ],
            ];
        });
    
        if ($request->wantsJson()) {
            return response()->json([
                'wallet' => $walletData,
                'budgets' => $budgetsData,
            ]);
        }
    
        return Inertia::render('Budget/Index', [
            'wallet' => $walletData,
            'budgets' => $budgetsData,
        ]);
    }  

    public function store(Request $request)
    {
        $user_id = $request->user()->id;
    
        $validatedData = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'time_range' => 'required|in:week,month,year,quarter,custom',
            'custom_from' => 'nullable|date_format:d/m/Y',
            'custom_to' => 'nullable|date_format:d/m/Y', 
        ]);
    
        if ($validatedData['time_range'] === 'custom') {
            $customFrom = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['custom_from']);
            $customTo = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['custom_to']);
            $validatedData['custom_from'] = $customFrom;
            $validatedData['custom_to'] = $customTo;
        }
    
        $budget = Budget::create(array_merge($validatedData, ['user_id' => $user_id]));
    
        return response()->json([
            'success' => true,
            'message' => 'Budget created successfully!', 
        ]);
    }
}