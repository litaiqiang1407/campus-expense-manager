<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Services\ReportService;
use App\Services\TransactionService;
use Inertia\Inertia;

class ReportsController extends Controller
{
    protected $walletService;
    protected $reportService;

    public function __construct(WalletService $walletService, ReportService $reportService)
    {
        $this->walletService = $walletService;
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $user_id = $request->user()->id; 

        $startDate = $request->input('startDate', null);  
        $endDate = $request->input('endDate', null);    
        $walletId = $request->input('walletId', null);  
    
        $reports = $this->reportService->getReports($user_id, $startDate, $endDate, $walletId);

        $wallets = $this->walletService->getWallets($user_id);

        if ($request->wantsJson()) {
            return response()->json([
                'reports' => $reports,
                'wallets' => $wallets,
            ]);
        }

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'wallets' => $wallets,
        ]);
    }

    public function category(Request $request)
    {
        $user_id = $request->user()->id; 

        $startDate = $request->input('startDate', null);  
        $endDate = $request->input('endDate', null);    
        $categoryId = $request->input('categoryId', null);  
    
        $reports = $this->reportService->getCategoryReports($user_id, $startDate, $endDate, $categoryId);

        if ($request->wantsJson()) {
            return response()->json([
                'reports' => $reports,
            ]);
        }

        return Inertia::render('Reports/Category', [
            'reports' => $reports,
        ]);
    }
}