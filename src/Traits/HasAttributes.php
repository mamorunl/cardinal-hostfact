<?php

namespace Tnpdigital\Cardinal\Hostfact\Traits;

use Carbon\Carbon;
use Carbon\CarbonInterface;

trait HasAttributes
{
    protected $dates = [];

    protected $attributes = [];

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
    protected function setAttribute($key, $value): void
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
        if($value instanceof CarbonInterface) {
            return $value;
        }

        if(empty($value)) {
            return $value;
        }

        if($this->isStandardDateFormat($value)) {
            $value = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        } else {
            $value = Carbon::createFromFormat('Y-m-d H:i:s', $value);
        }

        return $value;
    }

    /**
     * Add the date attributes to the attributes array.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function addDateAttributesToArray(array $attributes)
    {
        foreach ($this->dates as $key) {
            if (empty($attributes[$key])) {
                continue;
            }

            $attributes[$key] = $attributes[$key]->format('Y-m-d');
        }

        return $attributes;
    }

    /**
     * Determine if the given value is a standard date format.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isStandardDateFormat($value)
    {
        return preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value);
    }
}