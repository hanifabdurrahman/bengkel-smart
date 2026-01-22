<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Workshop extends Authenticatable
{
    use Notifiable;

    protected $table = 'workshops';
    protected $primaryKey = 'workshop_id';

    protected $fillable = [
        'workshop_name',
        'address',
        'phone_number',
        'email',
        'logo',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ================= RELASI (RELATIONSHIPS) =================

    public function customers()
    {
        return $this->hasMany(Customer::class, 'workshop_id', 'workshop_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'workshop_id', 'workshop_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'workshop_id', 'workshop_id')->latestOfMany('subscription_id');
    }
    // Ambil langganan aktif terakhir
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'workshop_id', 'workshop_id')
            ->where('status', 'active')
            ->whereDate('date_end', '>=', now())
            ->latestOfMany('subscription_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'workshop_id', 'workshop_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'workshop_id', 'workshop_id');
    }

    // Perbaikan nama method: sparepart -> spareparts (Jamak)
    public function spareparts()
    {
        return $this->hasMany(Sparepart::class, 'workshop_id', 'workshop_id');
    }

    // ================= LOGIKA AKSESOR (ATTRIBUTES) =================

    public function getIsPremiumAttribute()
    {
        // 1. Ambil langganan yang aktif
        $sub = $this->activeSubscription;

        // 2. Jika tidak ada langganan aktif, berarti FREE
        if (!$sub) {
            return false;
        }

        // 3. Cek Relasi Plan
        if (!$sub->plan) {
            return false;
        }
        // 4. Cek Harga Plan
        if ($sub->plan->price > 0) {
            return true;
        }

        return false;
    }

    /**
     * Helper Cek Status Aktif (Boolean)
     */
    public function hasActiveSubscription()
    {
        return $this->activeSubscription()->exists();
    }

    public function hasUsedFreePlan()
    {
        return $this->subscriptions()
            ->whereHas('plan', function ($query) {
                $query->where('price', '<=', 0);
            })
            ->exists();
    }
}
