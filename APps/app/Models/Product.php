<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    // auto-incrementing big integer per migration
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'product_code',
        'name',
        'description',
        'price',
        'stock_quantity',
        'category',
        'image',
        'admin_id',
        'brand_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // If it's already a full URL, return as is
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Convert storage path to full URL
        if (str_starts_with($this->image, 'storage/')) {
            return url($this->image);
        }

        if (str_starts_with($this->image, '/storage/')) {
            return url($this->image);
        }

        // Default: assume it's in storage/products
        return url('storage/products/' . $this->image);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }
}

