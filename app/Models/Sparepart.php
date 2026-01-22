<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sparepart extends Model
{
    use SoftDeletes;

    protected $table = 'spareparts';
    protected $primaryKey = 'sparepart_id';

    protected $fillable = [
        'workshop_id',
        'sparepart_code',
        'sparepart_name',
        'stock_quantity',
        'buying_price',
        'selling_price',
        'rack_location',
        'entry_date',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'stock_quantity' => 'integer',
        'buying_price'  => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    protected $dates = ['deleted_at'];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id', 'workshop_id');
    }

    public function details()
    {
        return $this->hasMany(SalesDetails::class, 'sparepart_id', 'sparepart_id');
    }
}