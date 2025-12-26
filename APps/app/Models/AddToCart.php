<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
    use HasFactory;

    protected $table = 'add_to_cart'; // because it's not plural (default would expect add_to_carts)
    protected $primaryKey = 'cart_id';
    public $incrementing = true; // auto-increment per migration
    protected $keyType = 'int';

    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
