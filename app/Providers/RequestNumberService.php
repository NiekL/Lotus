<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;

class RequestNumberService
{
    /**
     * Geef het volgende unieke request_number
     */
    public function getNextRequestNumber(): int
    {
        return DB::transaction(function () {
            $row = DB::table('request_number_tracker')->lockForUpdate()->first();

            if (!$row) {
                // Als er nog geen record bestaat, initialiseer het
                DB::table('request_number_tracker')->insert([
                    'highest_request_number' => 1760000000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $current = 1760000000;
            } else {
                $current = $row->highest_request_number;
            }

            $next = $current + 1;

            DB::table('request_number_tracker')->update([
                'highest_request_number' => $next,
                'updated_at' => now(),
            ]);

            return $next;
        });
    }
}
