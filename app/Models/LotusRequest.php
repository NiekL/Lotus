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
        'description',
        'start_date_time',
        'end_date_time',
        'applicant',
        'location',
        'street_name',
        'house_number',
        'postal_code',
        'number_of_people',
        'rate_group',
        'special_requests',
        'contact_person',
        'contact_phone',
        'contact_email',
        'payment_reference',
    ];

    // Optionally, you can also specify which attributes should be hidden from JSON responses
    protected $hidden = [
        // Hidden attributes
    ];

    // Optionally, you can specify which attributes are cast to specific types
    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        // Other attribute casts
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
