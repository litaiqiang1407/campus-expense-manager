<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function createTransaction($data, $userId)
    {
        // Tạo giao dịch
        $transaction = $this->transactionRepository->createTransaction($data, $userId);

        // Cập nhật số dư ví
        $this->transactionRepository->updateWalletBalance($data['wallet_id'], $data['amount']);

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
}
