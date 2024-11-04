<?php
namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;

use Illuminate\Http\Request;

class TransactionService
{
    protected $transactionRepository;
    protected $walletRepository;

    public function __construct(TransactionRepository $transactionRepository, WalletRepository $walletRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->walletRepository = $walletRepository;
    }

    public function getTransactionsAndWalletsByUser($userId)
    {
        $wallets = $this->walletRepository->getAllWallets($userId);

        $transactions = $this->transactionRepository->getTransactionsByUser($userId);

        $data = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'amount' => $transaction->amount,
                'type' => optional($transaction->category)->type,
                'wallet_id' => $transaction -> wallet_id,
                'note' => $transaction->note,
                'wallet_id' => $transaction ->wallet_id,
                'iconPath' => optional($transaction->category->icon)->path,
                'name' => optional($transaction->category)->name,
                'date' => $transaction->date,
            ];
        });

        return [
            'transactions' => $data,
            'wallets' => $wallets,
        ];
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
    public function calculateTotalBalance($userId)
    {
        return $this->transactionRepository->calculateTotalBalance($userId);
    }
}
