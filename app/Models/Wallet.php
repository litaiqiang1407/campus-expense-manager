<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'wallet_type_id', 'balance', 'user_id', 'icon_id'];

    public function walletType()
    {
        return $this->belongsTo(WalletType::class);
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
