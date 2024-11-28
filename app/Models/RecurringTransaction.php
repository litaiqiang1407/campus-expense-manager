<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recurring_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'amount',
        'type',
        'wallet_id',
        'user_id',
        'start_date',
        'interval',
        'frequency',
        'note',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'start_date' => 'date',
        //'interval' => 'integer',
        //'amount' => 'decimal:2',
    ];

    /**
     * Relationships.
     */

    // Category relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Wallet relationship
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
