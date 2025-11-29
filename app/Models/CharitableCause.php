<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharitableCause extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'icon',
        'organization',
        'website',
        'total_donations_received',
        'total_contributors',
        'is_active',
        'impact_metrics',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_donations_received' => 'decimal:2',
        'impact_metrics' => 'array',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'cause_id');
    }

    public function addDonation($amount, $contributorId = null)
    {
        $this->total_donations_received += $amount;

        // Track unique contributors
        if ($contributorId && !$this->donations()->where('user_id', $contributorId)->exists()) {
            $this->total_contributors++;
        }

        $this->save();
    }

    public function updateImpactMetrics($metrics)
    {
        $currentMetrics = $this->impact_metrics ?? [];
        $this->impact_metrics = array_merge($currentMetrics, $metrics);
        $this->save();
    }
}
