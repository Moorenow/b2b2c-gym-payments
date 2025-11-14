<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory;

    public const STATUSES = ['active', 'inactive', 'suspended', 'trial'];

    protected $fillable = [
        'plan_id',
        'name',
        'last_name',
        'identification_number',
        'email',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'membership_start_date',
        'current_period_end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'membership_start_date' => 'date',
        'current_period_end_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (Member $member): void {
            if (empty($member->membership_start_date)) {
                $member->membership_start_date = Carbon::now();
            }

            $plan = $member->plan ?? Plan::find($member->plan_id);

            if ($plan instanceof Plan) {
                $member->current_period_end_date = $member->calculatePeriodEndDate($plan);
            }
        });
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function calculatePeriodEndDate(Plan $plan): Carbon
    {
        $start = Carbon::parse($this->membership_start_date ?? Carbon::now());

        return match ($plan->frequency) {
            'semiannual' => $start->copy()->addMonths(6),
            'annual' => $start->copy()->addYear(),
            default => $start->copy()->addMonth(),
        };
    }
}
