<?php
namespace App\Services;

use App\Repositories\TransactionRecurringRepository;
use App\Repositories\WalletRepository;

use Illuminate\Http\Request;

class TransactionRecurringService
{
    protected $transactionRecurringRepository;
    protected $walletRepository;
    public function __construct(TransactionRecurringRepository $transactionRecurringRepository, WalletRepository $walletRepository)
    {
        $this->transactionRecurringRepository = $transactionRecurringRepository;
        $this->walletRepository = $walletRepository;
    }
    public function createRecurringTransaction($data, $userId)
    {
        //$category = $this->transactionRepository->getCategoryById($data['category_id']);

        $transaction = $this->transactionRecurringRepository->createRecurringTransaction($data, $userId);

        // if ($category->type === 'income') {
        //     $this->transactionRepository->updateWalletBalance($data['wallet_id'], $data['amount'], true); // true: cộng tiền
        // } else {
        //     $this->transactionRepository->updateWalletBalance($data['wallet_id'], $data['amount'], false); // false: trừ tiền
        // }

        return $transaction;
    }
}
