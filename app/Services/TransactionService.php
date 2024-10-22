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
        $this->walletRepository = $walletRepository; // Gán giá trị cho $walletRepository
    }

    public function getTransactionsAndWalletsByUser($userId)
    {
        // Lấy các ví của user
        $wallets = $this->walletRepository->getAllWallets($userId);

        // Lấy các giao dịch của user
        $transactions = $this->transactionRepository->getTransactionsByUser($userId);

        // Xử lý dữ liệu transaction
        $data = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'amount' => $transaction->amount,
                'type' => optional($transaction->category)->type,
                'note' => $transaction->note,
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
        // Lấy category để kiểm tra type (income/expense)
        $category = $this->transactionRepository->getCategoryById($data['category_id']);

        // Tạo giao dịch
        $transaction = $this->transactionRepository->createTransaction($data, $userId);

        // Nếu category type là 'income', cộng tiền, ngược lại nếu là 'expense', trừ tiền
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
}
