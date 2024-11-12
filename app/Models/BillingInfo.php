<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'billing_name', 'billing_contactperson', 'billing_phone',
        'billing_email', 'billing_address', 'billing_zipcode', 'billing_city'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
