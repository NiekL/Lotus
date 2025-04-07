<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'is_closed',
        'request_number',
    ];

    // Optionally, you can also specify which attributes should be hidden from JSON responses
    protected $hidden = [
        // Hidden attributes
    ];

    // De boot-methode voor het automatisch genereren van het factuurnummer
    protected static function booted()
    {
        static::creating(function ($lotusRequest) {
            // Haal het jaar van de huidige datum
            $year = now()->year;

            // Haal het laatst gebruikte factuurnummer voor dit jaar op
            $lastInvoiceNumber = DB::table('lotus_requests')
                ->whereYear('created_at', $year)
                ->orderBy('payment_mark', 'desc')
                ->value('payment_mark');

            // Controleer of het jaar 2025 is of een ander jaar
            if ($year == 2025) {
                // Als er al factuurnummers zijn, verhoog dan het nummer met 1
                // Anders begin bij 00200
                $nextInvoiceNumber = $lastInvoiceNumber
                    ? intval(substr($lastInvoiceNumber, -5)) + 1
                    : 200;
            } else {
                // Voor andere jaren beginnen we bij 00000
                $nextInvoiceNumber = $lastInvoiceNumber
                    ? intval(substr($lastInvoiceNumber, -5)) + 1
                    : 0;
            }

            // Genereer het nieuwe factuurnummer in het gewenste formaat
            $lotusRequest->payment_mark = $year . '-' . str_pad($nextInvoiceNumber, 5, '0', STR_PAD_LEFT);

        });
    }

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
