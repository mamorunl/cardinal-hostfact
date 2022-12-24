<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Tnpdigital\Cardinal\Hostfact\Client;
use Tnpdigital\Cardinal\Hostfact\Contracts\ModelContract;

class Debtor extends Model implements ModelContract
{
    protected $primaryKey = 'Identifier';

    /**
     * @param $id
     *
     * @return static
     * @throws \Exception
     */
    public static function find($id)
    {
        $response = Client::sendRequest('debtor', 'show', [
            'Identifier' => $id
        ]);

        if (strcasecmp($response['status'], 'success')) {
            throw new \Exception($response['errors'][0]);
        }

        $debtor = new static;
        $debtor->setRawAttributes($response['debtor']);

        return $debtor;
    }

    /**
     * @param $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public static function all($columns = [ '*' ]): Collection
    {
        $response = Client::sendRequest('debtor', 'list', [
            'limit' => 1000
        ]);

        if (strcasecmp($response['status'], 'success')) {
            throw new \Exception($response['errors'][0]);
        }

        $debtors = [];

        foreach ($response as $debtor_object) {
            $debtor = Debtor::find($debtor_object['Identifier']);
            $debtors[] = $debtor;
        }

        return new Collection($debtors);
    }
}