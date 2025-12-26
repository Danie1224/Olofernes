<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequestItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'return_request_item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'request_id',
        'order_item_id',
        'product_id',
        'quantity',
        'reason',
        'refund_amount',
        'status',
        'notes',
        'requested_at',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'requested_at' => 'datetime',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class, 'request_id', 'request_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
