<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    // CRITICAL FIX: Tells Eloquent that the primary key column is 'request_id'
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'customer_id',
        'processed_by',
        'reason',        // Kept for consistency, though 'notes' is used in store()
        'status',
        'refund_amount',
        'return_date',
        // --- ADDED REQUIRED FIELDS USED IN CONTROLLER ---
        'notes', // Used in the store method for the overall reason
        'total_refund_amount', // Used for updating the total refund
        'admin_notes', // Used in the Admin controller (approve/reject)
        // ------------------------------------------------
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'return_date' => 'date',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    public function items()
    {
        // Define the relationship by only specifying the foreign key 'request_id'
        // This is cleaner and sufficient since the local key is already defined as the primary key.
        return $this->hasMany(ReturnRequestItem::class, 'request_id'); 
    }

    public function user()
    {
        // If Customer and User are separate models, this is correct.
        return $this->belongsTo(User::class, 'customer_id', 'customer_id');
    }
}