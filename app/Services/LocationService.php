<?php

namespace App\Services;

use App\Models\Province;

class LocationService
{
    public static function getProvinces()
    {
        return Province::select([
            'id',
            'name',
        ])
            ->with('municipalities')
            ->orderBy('name', 'asc')->get();
    }
}
