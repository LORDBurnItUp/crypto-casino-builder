<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'min_bet',
        'max_bet',
        'house_edge',
        'game_config',
        'icon',
        'is_active',
        'total_plays',
        'total_wagered',
        'total_payout',
    ];

    protected $casts = [
        'min_bet' => 'decimal:2',
        'max_bet' => 'decimal:2',
        'house_edge' => 'decimal:2',
        'total_wagered' => 'decimal:2',
        'total_payout' => 'decimal:2',
        'game_config' => 'array',
        'is_active' => 'boolean',
    ];

    public function sessions()
    {
        return $this->hasMany(GameSession::class);
    }

    public function recordPlay($wagered, $payout)
    {
        $this->total_plays++;
        $this->total_wagered += $wagered;
        $this->total_payout += $payout;
        $this->save();
    }

    public function getActualHouseEdge()
    {
        if ($this->total_wagered == 0) return 0;

        $profit = $this->total_wagered - $this->total_payout;
        return ($profit / $this->total_wagered) * 100;
    }
}
