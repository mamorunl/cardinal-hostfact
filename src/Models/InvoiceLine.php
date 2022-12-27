<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

use Carbon\Carbon;

class InvoiceLine extends Model
{
    protected $dates = ['Date', 'StartPeriod'];

    public function __construct()
    {
        $this->attributes['date'] = Carbon::now();
        $this->attributes['number'] = 1;
        $this->attributes['periodicType'] = 'once'; // once || period
    }
}