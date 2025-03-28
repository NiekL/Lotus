<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotusRequest extends Model
{
    use HasFactory;
    // Specify which attributes are mass assignable
    protected $fillable = [
        'name',
        'customer_id',
        'description',
        'date',
        'arrival_time',
        'end_time',
        'amount_lotus',
        'payment_mark',
        'payment_mark_customer',
        'rate_group',
        'details',
        'city',
        'street_name',
        'house_number',
        'zipcode',
        'contact_person',
        'contact_phone',
        'contact_email',
        'filled_spots',
        'status',
        'is_closed'
    ];

    // Optionally, you can also specify which attributes should be hidden from JSON responses
    protected $hidden = [
        // Hidden attributes
    ];

    // Optionally, you can specify which attributes are cast to specific types
    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'is_closed' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function canUnregister(): bool
    {
        return $this->date->isAfter(now()->addDays(14));
    }
}
