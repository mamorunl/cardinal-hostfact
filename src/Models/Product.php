<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

use Tnpdigital\Cardinal\Hostfact\Client;
use Illuminate\Database\Eloquent\Collection;

class Product extends Model
{
    /**
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public static function list(array $params = []): Collection
    {
        $response = Client::sendRequest('product', 'list', [
                'limit' => 1000
            ] + $params);

        $products = [];
        $response = $response['products'];

        foreach ($response as $product_object) {
            $product = Product::show($product_object['Identifier']);
            $products[] = $product;
        }

        return new Collection($products);
    }
}