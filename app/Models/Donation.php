<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cause_id',
        'forum_gold_amount',
        'real_money_amount',
        'status',
        'certificate_message',
        'transaction_reference',
        'processed_at',
    ];

    protected $casts = [
        'forum_gold_amount' => 'decimal:2',
        'real_money_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cause()
    {
        return $this->belongsTo(CharitableCause::class, 'cause_id');
    }

    public function markAsCompleted($transactionReference = null)
    {
        $this->status = 'completed';
        $this->transaction_reference = $transactionReference;
        $this->processed_at = now();
        $this->save();

        // Update cause totals
        $this->cause->addDonation($this->real_money_amount, $this->user_id);
    }
}
