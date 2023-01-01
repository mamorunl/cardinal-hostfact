<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

use Tnpdigital\Cardinal\Hostfact\Client;
use Illuminate\Contracts\Support\Arrayable;
use Tnpdigital\Cardinal\Hostfact\Traits\HasAttributes;

abstract class Model implements Arrayable
{
    use HasAttributes;

    protected $excluded = [];

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
     * @param $attributes
     *
     * @return void
     */
    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function toArray()
    {
        $attributes = $this->addDateAttributesToArray($this->attributes);

        foreach ($this->excluded as $item) {
            unset($attributes[$item]);
        }

        return $attributes;
    }

    /**
     * @param $Identifier
     *
     * @return static
     * @throws \Exception
     */
    public static function show($Identifier): static
    {
        $class_name = strtolower(class_basename(get_called_class()));

        $response = Client::sendRequest($class_name, 'show', [
            'Identifier' => $Identifier
        ]);

        $debtor = new static;
        $debtor->setRawAttributes($response[$class_name]);

        return $debtor;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete(): bool
    {
        $class_name = strtolower(class_basename(get_called_class()));

        Client::sendRequest($class_name, 'delete', [
            'Identifier' => $this->Identifier
        ]);

        return true;
    }
}