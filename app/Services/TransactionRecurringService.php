<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Models\Category;
use App\Repositories\TransactionRecurringRepository;
use App\Repositories\WalletRepository;

class TransactionRecurringService
{
    protected $transactionRecurringRepository;
    protected $transactionRepository;
    protected $walletRepository;

    public function __construct(TransactionRepository $transactionRepository, TransactionRecurringRepository $transactionRecurringRepository, WalletRepository $walletRepository)
    {
        $this->transactionRecurringRepository = $transactionRecurringRepository;
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Tính toán tổng số lần lặp và tổng tiền cho các loại lặp khác nhau.
     */
    public function calculateRecurringTotal($data)
    {
        $amount = $data['amount'];
        $fr = $data['frequency'];
        $startDate = \DateTime::createFromFormat('d/m/Y h:i A', $data['start_date']);
        if (!$startDate) {
            throw new \Exception('Invalid start date format');
        }

        // Chuẩn hóa giá trị frequency từ type
        $type = strtolower(trim($data['type']));
        switch ($type) {
            case 'repeat daily':
                $frequency = 'daily';
                break;
            case 'repeat weekly':
                $frequency = 'weekly';
                break;
            case 'repeat monthly':
                $frequency = 'monthly';
                break;
            case 'repeat yearly':
                $frequency = 'yearly';
                break;
            default:
                throw new \Exception('Invalid frequency type');
        }

        $occurrences = 0;

        // Trường hợp interval là số lần lặp
        if (is_numeric($data['interval'])) {
            $occurrences = $data['interval'];
            $endDate = clone $startDate;

            // Tính toán ngày kết thúc dựa trên frequency
            switch ($frequency) {
                case 'daily':
                    $endDate->modify('+' . (($occurrences - 1) * $fr) . ' days');
                    break;
                case 'weekly':
                    $endDate->modify('+' . (($occurrences - 1) * 7) . ' days');
                    break;
                case 'monthly':
                    $endDate->modify('+' . ($occurrences - 1) . ' months');
                    break;
                case 'yearly':
                    $endDate->modify('+' . ($occurrences - 1) . ' years');
                    break;
            }
        } else {
            // Trường hợp interval là ngày kết thúc
            $endDate = \DateTime::createFromFormat('d/m/Y h:i A', $data['interval']);
            if (!$endDate) {
                throw new \Exception('Invalid end date format');
            }

            $currentDate = clone $startDate;

            // Tính toán số lần lặp dựa trên end_date và frequency
            switch ($frequency) {
                case 'daily':
                    $step = '+' . $fr . ' day';
                    break;
                case 'weekly':
                    $step = '+7 days';
                    break;
                case 'monthly':
                    $step = '+1 month';
                    break;
                case 'yearly':
                    $step = '+1 year';
                    break;
            }

            while ($currentDate <= $endDate) {
                $occurrences++;
                $currentDate->modify($step);
            }
        }

        // Tính tổng giá trị
        $total = $amount * $occurrences;

        return [
            'occurrences' => $occurrences,
            'total' => $total,
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s'),
        ];
    }

    public function getEditRecurringTransaction($transactionRecurringId)
    {
        $transaction = $this->transactionRecurringRepository->getEditRecurringTransaction($transactionRecurringId);

        if (!$transaction) {
            throw new \Exception('Transaction not found.');
        }

        if (is_string($transaction->interval)) {
            $repeatType = 'Until';
        } elseif (is_numeric($transaction->interval)) {
            if ((int) $transaction->interval === 30) {
                $repeatType = 'Forever';
            } else {
                $repeatType = 'Times';
            }
        } else {
            $repeatType = null;
        }

        $calculatedData = $this->calculateRecurringTotal([
            'amount' => $transaction->amount,
            'frequency' => $transaction->frequency,
            'start_date' => $transaction->start_date,
            'type' => $transaction->type,
            'interval' => $transaction->interval,
        ]);

        return [
            'id' => $transaction->id,
            'amount' => $transaction->amount,
            'category_id' => $transaction->category_id,
            'wallet_id' => $transaction->wallet_id,
            'type' => $transaction->type,
            'category_name' => optional($transaction->category)->name,
            'icon_path' => optional($transaction->category->icon)->path,
            'wallet_name' => optional($transaction->wallet)->name,
            'wallet_image' => optional($transaction->wallet->icon)->path,
            'start_date' => $transaction->start_date,
            'frequency' => $transaction->frequency,
            'note' => $transaction->note,
            'interval' => $transaction->interval,
            'repeatType' => $repeatType,
            'end_date' => $calculatedData['end_date'],
        ];
    }

    public function deleteTransaction(int $transactionId, int $userId): bool
    {
        $transaction = $this->getEditRecurringTransaction($transactionId);

        if (!$transaction) {
            throw new \Exception('Transaction not found.');
        }

        // Chỉ lấy các dữ liệu cần thiết để truyền vào hàm calculateRecurringTotal
        $data = [
            'amount' => $transaction['amount'],
            'start_date' => $transaction['start_date'],
            'type' => $transaction['type'],
            'interval' => $transaction['interval'],
            'frequency' => $transaction['frequency'],
        ];

        $formatData = $this->calculateRecurringTotal($data);
        $total = $formatData['total'];
        return $this->transactionRecurringRepository->deleteTransactionByIdAndUserId(
            $transactionId,
            $userId,
            $total
        );
    }
    public function createRecurringTransaction($data, $userId)
    {
        $category = $this->transactionRepository->getCategoryById($data['category_id']);
        $recurringData = $this->calculateRecurringTotal($data);
        $recurringtransaction = $this->transactionRecurringRepository->createRecurringTransaction($data, $userId);
        if ($category->type === 'income') {
            $this->transactionRepository->updateWalletBalance($data['wallet_id'], $recurringData['total'], true); // true: cộng tiền
        } else {
            $this->transactionRepository->updateWalletBalance($data['wallet_id'], $recurringData['total'], false); // false: trừ tiền
        }
        return $recurringtransaction;
    }
    public function getTransactionIndexRecurringTransaction($userId)
    {
        $recurringTransactions = $this->transactionRecurringRepository->getTransactionIndexRecurringTransaction($userId);

        $data = $recurringTransactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'amount' => $transaction->amount,
                'wallet_id' => $transaction->wallet_id,
                'start_date' => $transaction->start_date,
                'category_name' => $transaction->category->name ?? null,
                'icon_path' => $transaction->category->icon->path ?? null,
                'type' => optional($transaction->category)->type,
            ];
        });

        return [
            'transactionsRecurring' => $data,
        ];
    }
    public function getIndexRecurringTransaction($userId)
    {
        $recurringTransactions = $this->transactionRecurringRepository->getIndexRecurringTransaction($userId);

        $data = $recurringTransactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'repeat_type' => $transaction->type,
                'amount' => $transaction->amount,
                'wallet_id' => $transaction->wallet_id,
                'frequency' => is_numeric($transaction['frequency']) ? (int) $transaction['frequency'] : $transaction['frequency'],
                'interval' => $transaction->interval,
                'date' => $transaction->date, // Sử dụng 'date' đã đổi tên ở repository
                'name' => $transaction->category->name ?? null,
                'iconPath' => $transaction->category->icon->path ?? null,
                'type' => optional($transaction->category)->type,
                'note' => $transaction->note,
            ];
        });

        return [
            'transactionsRecurring' => $data,
        ];
    }
    public function getDetailsRecurringTransaction($transactionRecurringId)
    {
        $transaction = $this->transactionRecurringRepository->getDetailsRecurringTransaction($transactionRecurringId);

        if (!$transaction) {
            throw new \Exception('Transaction not found.');
        }

        return [
            'id' => $transaction->id,
            'amount' => $transaction->amount,
            'category_name' => optional($transaction->category)->name,
            'icon_path' => optional($transaction->category->icon)->path,
            'wallet_name' => optional($transaction->wallet)->name,
            'wallet_image' => optional($transaction->wallet->icon)->path,
        ];
    }
    public function updateRecurringTransaction($transactionRecurringId, $data)
    {
        $transaction = $this->getEditRecurringTransaction($transactionRecurringId);
        if (!$transaction) {
            throw new \Exception('Transaction not found.');
        }

        $oldType = $this->getCategoryType($transaction['category_id']);
        $newType = $this->getCategoryType($data['category_id']);
        $olddata = [
            'amount' => $transaction['amount'],
            'start_date' => $transaction['start_date'],
            'type' => $transaction['type'],
            'interval' => $transaction['interval'],
            'frequency' => is_numeric($transaction['frequency']) ? (int) $transaction['frequency'] : $transaction['frequency'],
            'wallet_id' => $transaction['wallet_id']
        ];

        $formatData = $this->calculateRecurringTotal($olddata);
        $newformatData = $this->calculateRecurringTotal($data);

        $newTotal = $newformatData['total'];
        $oldWalletId = $transaction['wallet_id'];
        $newWalletId = $data['wallet_id'];
        $oldTotalAmount = $formatData['total'];

        // Gọi phương thức cập nhật từ repository để thực hiện cập nhật giao dịch và điều chỉnh số dư ví
        return $this->transactionRecurringRepository->updateRecurringTransaction(
            $transactionRecurringId,
            $newTotal,
            $oldTotalAmount,
            $data,
            $oldWalletId,
            $newWalletId,
            $oldType,
            $newType
        );
    }

    public function getCategoryType($categoryId)
    {
        $category = Category::find($categoryId);
        return $category ? $category->type : null;
    }
    public function getTransactionDetails($data)
    {
        $startDate = \DateTime::createFromFormat('d/m/Y h:i A', $data['date']);
        if (!$startDate) {
            throw new \Exception('Invalid start date format');
        }

        $type = strtolower(trim($data['repeat_type']));
        $transactions = [];

        if (is_numeric($data['interval'])) {
            // Loại 2: interval là số lần lặp
            $occurrences = $data['interval'];
            $currentDate = clone $startDate;

            switch ($type) {
                case 'repeat daily':
                    $step = '+' . (int)$data['frequency'] . ' days';
                    break;
                case 'repeat weekly':
                    $step = '+' . ((int)$data['frequency'] * 7) . ' days';
                    break;
                case 'repeat monthly':
                    $step = '+' . (int)$data['frequency'] . ' months';
                    break;
                case 'repeat yearly':
                    $step = '+' . (int)$data['frequency'] . ' years';
                    break;
                default:
                    throw new \Exception('Invalid repeat type');
            }

            for ($i = 0; $i < $occurrences; $i++) {
                $transactions[] = [
                    'id' => $data['id'],
                    'amount' => $data['amount'],
                    'repeat_type' => ucfirst($type),
                    'date' => $currentDate->format('Y-m-d H:i:s'),
                    'name' => $data['name'],
                    'iconPath' => $data['iconPath'],
                    'type' => $data['type'],
                    'wallet_id' => $data['wallet_id'],
                    'note' => $data['note'],
                ];
                $currentDate->modify($step);
            }
        } else {
            // Loại 1: interval là ngày kết thúc
            $endDate = \DateTime::createFromFormat('d/m/Y h:i A', $data['interval']);
            if (!$endDate) {
                throw new \Exception('Invalid end date format');
            }

            $currentDate = clone $startDate;
            $step = '';

            switch ($type) {
                case 'repeat daily':
                    $step = '+' . $data['frequency'] . ' days';
                    break;
                case 'repeat weekly':
                    $step = '+7 days';
                    break;
                case 'repeat monthly':
                    $step = '+1 month';
                    break;
                case 'repeat yearly':
                    $step = '+1 year';
                    break;
                default:
                    throw new \Exception('Invalid repeat type');
            }

            while ($currentDate <= $endDate) {
                $transactions[] = [
                    'id' => $data['id'],
                    'amount' => $data['amount'],
                    'repeat_type' => ucfirst($type),
                    'date' => $currentDate->format('Y-m-d H:i:s'),
                    'name' => $data['name'],
                    'iconPath' => $data['iconPath'],
                    'type' => $data['type'],
                    'wallet_id' => $data['wallet_id'],
                    'note' => $data['note'],
                ];
                $currentDate->modify($step);
            }
        }

        return $transactions;
    }
}
