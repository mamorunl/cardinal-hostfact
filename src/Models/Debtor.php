<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

use Illuminate\Database\Eloquent\Collection;
use Tnpdigital\Cardinal\Hostfact\Client;
use Tnpdigital\Cardinal\Hostfact\Contracts\ModelContract;

class Debtor extends Model implements ModelContract
{
    /**
     * @param array $params
     *
     * @return $this
     * @throws \Exception
     */
    public static function add(array $params = []): static
    {
        if(!array_key_exists('CompanyName', $params) && !array_key_exists('SurName', $params)) {
            throw new \Exception('CompanyName or SurName are required fields');
        }

        $response = Client::sendRequest('debtor', 'add', $params);

        if(strcasecmp($response['status'], 'success')) {
            throw new \Exception($response['errors'][0]);
        }

        $debtor = new static;
        $debtor->setRawAttributes($response['debtor']);

        return $debtor;
    }

    /**
     * @param array $params
     *
     * @return $this
     * @throws \Exception
     */
    public function edit(array $params = []): self
    {
        if(
            (!array_key_exists('CompanyName', $params) && !isset($this->attributes['CompanyName'])) &&
            (!array_key_exists('SurName', $params) && !isset($this->attributes['SurName']))) {
            throw new \Exception('CompanyName or SurName are required fields');
        }

        $params['Identifier'] = $this->attributes['Identifier'];

        $response = Client::sendRequest('debtor', 'edit', $params);

        if(strcasecmp($response['status'], 'success')) {
            throw new \Exception($response['errors'][0]);
        }

        $this->setRawAttributes($params);

        return $this;
    }

    /**
     * @param $Identifier
     *
     * @return static
     * @throws \Exception
     */
    public static function show($Identifier): static
    {
        $response = Client::sendRequest('debtor', 'show', [
            'Identifier' => $Identifier
        ]);

        if (strcasecmp($response['status'], 'success')) {
            throw new \Exception($response['errors'][0]);
        }

        $debtor = new static;
        $debtor->setRawAttributes($response['debtor']);

        return $debtor;
    }

    /**
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public static function list(array $params = []): Collection
    {
        $response = Client::sendRequest('debtor', 'list', [
            'limit' => 1000
        ] + $params);

        if (strcasecmp($response['status'], 'success')) {
            throw new \Exception($response['errors'][0]);
        }

        $debtors = [];
        $response = $response['debtors'];

        foreach ($response as $debtor_object) {
            $debtor = Debtor::show($debtor_object['Identifier']);
            $debtors[] = $debtor;
        }

        return new Collection($debtors);
    }
}