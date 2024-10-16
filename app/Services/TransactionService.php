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
        return $this->transactionRepository->createTransaction($data, $userId);
    }
    public function getTransactionById($transactiontId)
    {
        return $this->transactionRepository->getTransactionById($transactiontId);
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
