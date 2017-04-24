<?php
namespace LaraMod\AdminOrders\Models;

use LaraMod\AdminProducts\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersItems extends Model
{
    public $timestamps = false;
    protected $table = 'orders_items';

    use SoftDeletes;
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'qty' => 'double',
        'price' => 'double',
        'weight' => 'double'
    ];

    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function order()
    {
        return $this->hasOne(Orders::class,'id', 'order_id');
    }

}