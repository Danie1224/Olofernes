<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $primaryKey = 'shipping_id';
    public $incrementing = true; // auto-increment per migration
    protected $keyType = 'int';

    protected $fillable = [
        'order_id', 
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'tracking_number',
        'shipping_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Each shipping belongs to an order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
