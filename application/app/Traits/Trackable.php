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
        static::creating(function($item) {
            $item->created_by = Auth::id();
        });

        static::updating(function($item) {
            $item->updated_by = Auth::id();
        });
    }
}