<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AsepAvailability
{
    public static function enabled(): bool
    {
        if (! Schema::hasTable('app_settings')) {
            return true;
        }

        return DB::table('app_settings')->where('key', 'asep_enabled')->value('value') !== '0';
    }
}
