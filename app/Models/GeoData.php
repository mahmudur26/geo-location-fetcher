<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoData extends Model
{
    protected $fillable = [
        'ip',
        'latitude',
        'longitude',
        'continent',
        'country',
        'state',
        'state_district',
        'town',
        'full_formatted_address',
        'accuracy_in_meter',
    ];
}
