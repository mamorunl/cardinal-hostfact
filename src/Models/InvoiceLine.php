<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

class InvoiceLine extends Model
{
    protected $dates = ['Date', 'StartPeriod'];

    protected $excluded = ['PeriodicID', 'EndPeriod'];
}