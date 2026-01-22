<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDetails extends Model
{
    protected $table = 'sales_details';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'transaction_id',
        'sparepart_id',
        'jumlah',
        'harga_satuan',
        'current_buying_price',
        'sub_total',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id', 'sparepart_id')->withTrashed();;
    }
}