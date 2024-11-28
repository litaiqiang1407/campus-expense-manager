<?php
namespace App\Services;
use App\Repositories\TransactionRepository;
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
    private function calculateRecurringTotal($data)
    {
        $amount = $data['amount'];
        $startDate = \DateTime::createFromFormat('d/m/Y h:i A', $data['start_date']);
        if (!$startDate) {
            throw new \Exception('Invalid start date format');
        }
        if (is_numeric($data['interval'])) {
            $occurrences = $data['interval'];
            $endDate = clone $startDate;
            $frequency = $data['frequency'];
            $endDate->modify('+' . ($occurrences - 1) * $frequency . ' days');
        } else {
            $endDate = \DateTime::createFromFormat('d/m/Y h:i A', $data['interval']);
            if (!$endDate) {
                throw new \Exception('Invalid end date format');
            }
            $frequency = $data['frequency'];
            $occurrences = 0;
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $occurrences++;
                $currentDate->modify('+' . $frequency . ' days');
            }
        }
        $total = $amount * $occurrences;
        return [
            'occurrences' => $occurrences,
            'total' => $total,
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s'),
        ];
    }
    public function createRecurringTransaction($data, $userId)
    {
        $category = $this->transactionRepository->getCategoryById($data['category_id']);
        $recurringData = $this->calculateRecurringTotal($data);
        $data['amount'] = $recurringData['total'];
        $recurringtransaction = $this->transactionRecurringRepository->createRecurringTransaction($data, $userId);
        if ($category->type === 'income') {
            $this->transactionRepository->updateWalletBalance($data['wallet_id'], $data['amount'], true); // true: cộng tiền
        } else {
            $this->transactionRepository->updateWalletBalance($data['wallet_id'], $data['amount'], false); // false: trừ tiền
        }
        return $recurringtransaction;
    }
}
