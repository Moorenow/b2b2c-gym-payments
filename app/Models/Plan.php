<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public const TIERS = ['normal', 'plus', 'ultra'];
    public const FREQUENCIES = ['monthly', 'semiannual', 'annual'];

    public const DEFAULT_BASE_PRICES = [
        'normal' => 300,
        'plus' => 400,
        'ultra' => 600,
    ];

    public const FREQUENCY_DISCOUNTS = [
        'monthly' => 0,
        'semiannual' => 10,
        'annual' => 20,
    ];

    protected $fillable = [
        'name',
        'tier',
        'frequency',
        'base_price',
        'discount_percent',
        'final_price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'integer',
        'discount_percent' => 'integer',
        'final_price' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Plan $plan): void {
            $plan->base_price = $plan->base_price ?? $plan->suggestedBasePrice();
            $plan->discount_percent = $plan->resolveDiscountPercent();
            $plan->final_price = $plan->calculateFinalPrice();
        });
    }

    public function suggestedBasePrice(): int
    {
        return (int) (static::DEFAULT_BASE_PRICES[$this->tier] ?? $this->base_price ?? 0);
    }

    public function resolveDiscountPercent(): int
    {
        $provided = $this->discount_percent ?? null;

        if ($provided !== null && $provided > 0) {
            return (int) $provided;
        }

        return static::FREQUENCY_DISCOUNTS[$this->frequency] ?? 0;
    }

    public function calculateFinalPrice(): int
    {
        $base = max($this->base_price ?? $this->suggestedBasePrice(), 0);
        $discountRate = max($this->discount_percent ?? 0, 0) / 100;
        $final = (int) round($base * (1 - $discountRate));

        return max($final, 0);
    }
}
