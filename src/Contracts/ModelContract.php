<?php

namespace Tnpdigital\Cardinal\Hostfact\Contracts;

interface ModelContract
{
    public static function add(array $params);
    public function edit(array $params);
    public static function show($Identifier);
    public static function list(array $params);
}