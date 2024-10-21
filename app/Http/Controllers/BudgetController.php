<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Wallet; 
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Services\BudgetService;

class BudgetController extends Controller
{    
    protected $budgetService;
    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $walletId = $request->input('walletId');

        $budgetOverview = $this->budgetService->getBudgetOverview($userId, $walletId);

        if ($request->wantsJson()) {
            return response()->json($budgetOverview);
        }

        return Inertia::render('Budget/Index', $budgetOverview);
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

