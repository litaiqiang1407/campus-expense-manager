<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'wallet_type_name', 'balance', 'user_id', 'icon_id'];

    const TOTAL_WALLET_NAME = 'Total';
    const TOTAL_WALLET_ICON = '/assets/icon/total.png';

    const DEFAULT_WALLET_NAME = 'Cash';

    const DEFAULT_WALLET_ICON = '/assets/icon/default-wallet.png';

    public function isTotalWallet()
    {
        return $this->name === self::TOTAL_WALLET_NAME;
    }

    public function delete()
    {
        if ($this->isTotalWallet()) {
            return false;
        }

        return parent::delete();
    }

    public static function hasNonTotalWallet($userId)
    {
        return self::where('user_id', $userId)
            ->where('name', '!=', self::TOTAL_WALLET_NAME)
            ->exists();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function icon()
    {
        return $this->belongsTo(Icon::class);
    }
}
