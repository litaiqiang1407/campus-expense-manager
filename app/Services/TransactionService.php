<?php
namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use App\Services\TransactionRecurringService;
use Illuminate\Http\Request;

class TransactionService
{
    protected $transactionRepository;
    protected $walletRepository;

    protected $transactionRecurringService;
    public function __construct(TransactionRepository $transactionRepository, WalletRepository $walletRepository, TransactionRecurringService $transactionRecurringService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->walletRepository = $walletRepository;
        $this->transactionRecurringService = $transactionRecurringService;
    }
    public function getTransactionEdit($transactionId)
    {
        $transaction = $this->transactionRepository->getTransactionById($transactionId);

        return [
            'id' => $transaction->id,
            'amount' => $transaction->amount,
            'category_id'=> $transaction->category_id,
            'type' => optional($transaction->category)->type,
            'wallet_name' => optional($transaction->wallet)->name,
            'wallet_id' => $transaction->wallet_id,
            'note' => $transaction->note,
            'iconPath' => optional($transaction->category->icon)->path,
            'name' => optional($transaction->category)->name,
            'date' => $transaction->date,
        ];
    }

    public function getTransactionDetails($transactionId)
{
    $transaction = $this->transactionRepository->getTransactionById($transactionId);

    return [
        'id' => $transaction->id,
        'amount' => $transaction->amount,
        'type' => optional($transaction->category)->type,
        'wallet_name' => optional($transaction->wallet)->name,
        'note' => $transaction->note,
        'iconPath' => optional($transaction->category->icon)->path,
        'walletIcon' => optional($transaction->wallet->icon)->path,
        'category_name' => optional($transaction->category)->name,
        'date' => $transaction->date,
    ];
}

    public function getTransactionsAndWalletsByUser($userId)
    {
        $wallets = $this->walletRepository->getAllWallets($userId);

        $transactions = $this->transactionRepository->getTransactionsByUser($userId);

        $rtdata = $this->transactionRecurringService->getIndexRecurringTransaction($userId);

        $data1 = [
            "id" => 122,
            "amount" => 5022,
            "wallet_id" => 29,
            "frequency" => 30, // Khoảng cách lặp lại (30 ngày)
            "interval" => 1,  // Số lần lặp lại
            "start_date" => "03/12/2024 12:00 AM",
            "category_name" => "Gas Bill",
            "icon_path" => "/assets/icon/expense/gas_bill.png",
            "type" => "expense"
        ];
        //dd($rtdata);
        $recurringTransaction = $this->transactionRecurringService->getTransactionDetails($data1);
        dd($recurringTransaction);
        $data = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'amount' => $transaction->amount,
                'type' => optional($transaction->category)->type,
                'wallet_id' => $transaction -> wallet_id,
                'note' => $transaction->note,
                'iconPath' => optional($transaction->category->icon)->path,
                'name' => optional($transaction->category)->name,
                'date' => $transaction->date,
            ];
        });

        return [
            'recurringTransaction' => $recurringTransaction,
            'transactions' => $data,
            'wallets' => $wallets,
        ];
    }
    public function deleteTransaction($transactionId, $userId)
    {
        $this->transactionRepository->deleteTransaction($transactionId);
    }
    public function createTransaction($data, $userId)
    {
        $category = $this->transactionRepository->getCategoryById($data['category_id']);

        $transaction = $this->transactionRepository->createTransaction($data, $userId);

        if ($category->type === 'income') {
            $this->transactionRepository->updateWalletBalance($data['wallet_id'], $data['amount'], true); // true: cộng tiền
        } else {
            $this->transactionRepository->updateWalletBalance($data['wallet_id'], $data['amount'], false); // false: trừ tiền
        }

        return $transaction;
    }

    public function getTransactionById($transactionId)
    {
        return $this->transactionRepository->getTransactionById($transactionId);
    }

    public function updateTransaction($transactionId, $data)
    {
        return $this->transactionRepository->updateTransaction($transactionId, $data);
    }

    public function getCategories()
    {
        return $this->transactionRepository->getCategories();
    }

    public function getWalletsByUser($userId)
    {
        return $this->transactionRepository->getWalletsByUser($userId);
    }

    public function getRecentTransactions($userId)
    {
        return $this->transactionRepository->getRecentTransactions($userId);
    }

    public function getTopSpending($userId, $filter)
    {
        return $this->transactionRepository->getTopSpending($userId, $filter);
    }

    public function getReportTrending($userId)
    {
        return $this->transactionRepository->getReportTrending($userId);
    }

    public function getReportSpending($userId)
    {
        return $this->transactionRepository->getReportSpending($userId);
    }

    public function calculateTotalBalance($userId)
    {
        return $this->transactionRepository->calculateTotalBalance($userId);
    }
}
