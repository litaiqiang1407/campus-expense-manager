<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Services\TransactionService;
use Inertia\Inertia;

class HomeController extends Controller
{
    protected $walletService;
    protected $transactionService;

    public function __construct(WalletService $walletService, TransactionService $transactionService)
    {
        $this->walletService = $walletService;
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $user_id = $request->user()->id; 

        $filter = $request->input('filter', 'month'); 

        $totalBalance = $this->transactionService->calculateTotalBalance($user_id);

        $walletList = $this->walletService->getThreeWallets($user_id);

        $topSpending = $this->transactionService->getTopSpending($user_id, $filter);

        $recentTransactions = $this->transactionService->getRecentTransactions($user_id);

        if ($request->wantsJson()) {
            return response()->json([
                'totalBalance' => $totalBalance,
                'walletList' => $walletList,
                'recentTransactions' => $recentTransactions,
                'topSpending' => $topSpending,
            ]);
        }

        return Inertia::render('Home/Index', [
            'totalBalance' => $totalBalance,
            'walletList' => $walletList,
            'recentTransactions' => $recentTransactions,
            'topSpending' => $topSpending,
        ]);
    }

    public function report (Request $request)
    {
        $user_id = $request->user()->id;

        $reportTrending = $this->transactionService->getReportTrending($user_id);

        $reportSpending = $this->transactionService->getReportSpending($user_id);

        if ($request->wantsJson()) {
            return response()->json([
                'reportTrending' => $reportTrending,
                'reportSpending' => $reportSpending,
            ]);
        }

        return Inertia::render('Home/Components/Report/Index', [
            'reportTrending' => $reportTrending,
            'reportSpending' => $reportSpending,
        ]);
    }
}