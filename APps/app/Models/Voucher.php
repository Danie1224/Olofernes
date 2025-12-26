<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $primaryKey = 'voucher_id';
    public $incrementing = true; // auto-increment per migration
    protected $keyType = 'int';

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'usage_limit',
        'usage_count',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function userVouchers()
    {
        return $this->hasMany(UserVoucher::class, 'voucher_id', 'voucher_id');
    }

    public function voucherUsages()
    {
        return $this->hasMany(VoucherUsage::class, 'voucher_id', 'voucher_id');
    }
}

