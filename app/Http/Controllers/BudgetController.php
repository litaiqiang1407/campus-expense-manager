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
}
