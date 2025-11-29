<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'bet_amount',
        'win_amount',
        'profit',
        'game_data',
        'server_seed',
        'client_seed',
        'nonce',
        'result_hash',
        'is_win',
    ];

    protected $casts = [
        'bet_amount' => 'decimal:2',
        'win_amount' => 'decimal:2',
        'profit' => 'decimal:2',
        'game_data' => 'array',
        'is_win' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function generateProvablyFairSeeds($clientSeed = null)
    {
        $this->server_seed = bin2hex(random_bytes(32));
        $this->client_seed = $clientSeed ?? bin2hex(random_bytes(16));
        $this->nonce = (string) time();
        $this->result_hash = hash('sha256', $this->server_seed . $this->client_seed . $this->nonce);
    }

    public function verifyFairness()
    {
        $expectedHash = hash('sha256', $this->server_seed . $this->client_seed . $this->nonce);
        return $expectedHash === $this->result_hash;
    }
}
