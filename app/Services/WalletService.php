<?php

namespace App\Services;

use App\Repositories\WalletRepository;

class WalletService
{
    protected $walletRepository;

    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function userHasWallet($userId)
    {
        return $this->walletRepository->userHasWallet($userId);
    }

    public function getWallets($userId, $limit = null)
    {
        return $this->walletRepository->getAllWallets($userId, $limit);
    }

    public function getThreeWallets($userId)
    {
        return $this->walletRepository->getThreeWallets($userId);
    }

    public function getWalletTypes()
    {
        return $this->walletRepository->getWalletTypes();
    }

    public function createWallet($data, $userId)
    {
        if ($this->walletRepository->walletExistsWithName($userId, $data['name'])) {
            return [
                'success' => false,
                'message' => 'Wallet name already exists.'
            ];
        }

        $wallet = $this->walletRepository->createWallet($data, $userId);

        $this->walletRepository->recalculateTotalWalletBalance($userId);

        return [
            'success' => true,
            'message' => 'Wallet created successfully.',
            'wallet' => $wallet
        ];
    }

    public function getWalletById($walletId)
    {
        return $this->walletRepository->getWalletById($walletId);
    }

    public function updateWallet($walletId, $data, $userId)
    {
        $wallet = $this->walletRepository->updateWallet($walletId, $data);

        $this->walletRepository->recalculateTotalWalletBalance($userId);

        return $wallet;
    }

    public function getIcons()
    {
        return $this->walletRepository->getIcons();
    }

    public function deleteWallet($walletId, $userId)
    {
        $this->walletRepository->deleteWallet($walletId);

        $this->walletRepository->recalculateTotalWalletBalance($userId);
    }

    public function recalculateTotalWalletBalance($userId)
    {
        return $this->walletRepository->recalculateTotalWalletBalance($userId);
    }

    public function searchWallets($userId, $search, $limit = null)
    {
        return $this->walletRepository->searchWallets($userId, $search, $limit);
    }
}
