<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function user() { 
        return $this->belongsTo(User::class); 
    }
    
    public function items() { 
        return $this->hasMany(OrderItem::class); 
    }
    
    // Accessor for customer email
    public function getCustomerEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }
    
    // Accessor for customer name
    public function getCustomerNameAttribute()
    {
        return $this->user ? $this->user->name : 'Guest';
    }
    
    // Accessor for customer address (use shipping address)
    public function getCustomerAddressAttribute()
    {
        return $this->shipping_address;
    }
    
    // Accessor for customer phone
    public function getCustomerPhoneAttribute()
    {
        return $this->shipping_phone;
    }
}
