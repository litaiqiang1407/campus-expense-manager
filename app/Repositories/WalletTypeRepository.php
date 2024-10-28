<?php
namespace App\Repositories;

use App\Models\WalletType;

class WalletTypeRepository
{
    public function getWalletTypes()
    {
        return WalletType::select('id', 'name')->get();
    }

    public function getDefaultWalletTypeId()
    {
        $defaultWalletType = WalletType::first();
        return $defaultWalletType ? $defaultWalletType->id : null;
    }
}
