<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    public $incrementing = true; // auto increment per migration
    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',
        'admin_id',
        'product_id',
        'quantity',
        'total_price',
        'status',
        'payment_method',
        'order_date',
        'completed_at',
        'completed_by_admin_id',
        'cancelled_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'order_date' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function completedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'completed_by_admin_id', 'admin_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    /**
     * Mark order as completed by an admin
     */
    public function markAsCompleted($adminId)
    {
        return $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by_admin_id' => $adminId,
        ]);
    }

    /**
     * Check if order is already completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Get completion details
     */
    public function getCompletionDetails()
    {
        return [
            'completed_at' => $this->completed_at,
            'completed_by_admin_id' => $this->completed_by_admin_id,
            'completed_by_admin' => $this->completedByAdmin ? [
                'admin_id' => $this->completedByAdmin->admin_id,
                'name' => $this->completedByAdmin->name ?? 'Admin',
                'email' => $this->completedByAdmin->email ?? null,
            ] : null,
        ];
    }
}

