<?php

namespace Tnpdigital\Cardinal\Hostfact\Traits;

use Carbon\Carbon;

trait HasAttributes
{
    protected $dates = [];

    /**
     * Determine if the given attribute is a date or date castable.
     *
     * @param  string  $key
     * @return bool
     */
    protected function isDateAttribute($key): bool
    {
        return in_array($key, $this->dates, true);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function setAttribute($key, $value): void
    {
        if(!is_null($value) && $this->isDateAttribute($key)) {
            $value = $this->setDateTimeAttribute($value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * @param $value
     *
     * @return \Carbon\Carbon|false|mixed
     */
    protected function setDateTimeAttribute($value)
    {
        if($value instanceof Carbon) {
            return $value;
        }

        return empty($value) ? $value : Carbon::createFromFormat('Y-m-d', $value);
    }
}