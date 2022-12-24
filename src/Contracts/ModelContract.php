<?php

namespace Tnpdigital\Cardinal\Hostfact\Contracts;

interface ModelContract
{
    public static function find($id);
    public static function all();
}