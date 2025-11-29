<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'cost_gold',
        'icon',
        'item_data',
        'duration_days',
        'is_active',
        'purchase_count',
    ];

    protected $casts = [
        'cost_gold' => 'decimal:2',
        'item_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function purchases()
    {
        return $this->hasMany(UserPurchase::class, 'item_id');
    }

    public function recordPurchase()
    {
        $this->purchase_count++;
        $this->save();
    }
}
