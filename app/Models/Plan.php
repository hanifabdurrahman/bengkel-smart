<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';
    protected $primaryKey = 'plan_id';
    protected $fillable = [
        'plan_name',
        'price',
        'duration_days',
        'features',
        'is_popular',
        'badge',
        'description',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'badge' => 'string',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id', 'plan_id');
    }
}
