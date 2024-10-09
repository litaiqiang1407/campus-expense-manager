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

    public function getWallets()
    {
        return $this->walletRepository->getAllWallets();
    }

    public function getWalletTypes()
    {
        return $this->walletRepository->getWalletTypes();
    }

    public function createWallet($data, $userId)
    {
        return $this->walletRepository->createWallet($data, $userId);
    }

    public function getWalletById($walletId)
    {
        return $this->walletRepository->getWalletById($walletId);
    }

    public function updateWallet($walletId, $data)
    {
        return $this->walletRepository->updateWallet($walletId, $data);
    }

    public function getIcons()
    {
        return $this->walletRepository->getIcons();
    }
}
