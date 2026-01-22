<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'workshop_id',
        'customer_name',
        'address',
        'phone_number',
        'email',
        'vehicle',
        'license_plate',
        'year',
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id', 'workshop_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'customer_id', 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'customer_id');
    }

    public function prunable()
    {
        // Hapus permanen data yang sudah di-soft delete LEBIH DARI 2 TAHUN yang lalu
        return static::where('deleted_at', '<=', now()->subYears(2));
    }
}