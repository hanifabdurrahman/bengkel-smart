<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'service_id';
    protected $fillable = [
        'workshop_id',
        'customer_id',
        'transaction_id',
        'kode_servis',
        'tanggal_masuk',
        'waktu_selesai',
        'jenis_servis',
        'keluhan',
        'biaya_jasa',
        'status',
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id', 'workshop_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
