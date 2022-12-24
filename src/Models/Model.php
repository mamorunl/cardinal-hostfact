<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

abstract class Model
{
    protected $attributes;

    public function setRawAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function save()
    {
        //
    }

    public function __get($name)
    {
        if(isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function setAttribute($key, $value): void
    {
        $this->attributes[$key] = $value;
    }
}