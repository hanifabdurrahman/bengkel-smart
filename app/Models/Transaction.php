<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'workshop_id',
        'customer_id',
        'tanggal',
        'jenis_transaksi',
        'total_sparepart',
        'total_jasa',
        'diskon',
        'total_akhir',
        'status_pembayaran',
    ];

    /** CASTS */
    protected $casts = [
        'tanggal' => 'date',
        'total_sparepart' => 'decimal:2',
        'total_jasa' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_akhir' => 'decimal:2',
    ];

    /** RELATIONSHIPS */
    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id', 'workshop_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id')->withTrashed();
    }

    public function services()
    {
        return $this->hasOne(Service::class, 'transaction_id', 'transaction_id');
    }

    public function salesDetails()
    {
        return $this->hasMany(SalesDetails::class, 'transaction_id', 'transaction_id');
    }
}