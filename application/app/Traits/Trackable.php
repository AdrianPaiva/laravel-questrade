<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Update the Model's created_by and updated_by fields
 */
trait Trackable
{
    public static function bootTrackable()
    {
        $user_id = Auth::id();

        static::creating(function ($item) use ($user_id) {
            $item->created_by = $user_id;
        });

        static::updating(function ($item) use ($user_id) {
            $item->updated_by = $user_id;
        });
    }
}
