<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

use Illuminate\Database\Eloquent\Collection;
use Tnpdigital\Cardinal\Hostfact\Client;

class Debtor extends Model
{
    /**
     * @return $this
     * @throws \Exception
     */
    public function create(): self
    {
        if (isset($this->Identifier)) {
            throw new \Exception('Identifier already set. Use UPDATE instead');
        }

        if (!array_key_exists('CompanyName', $this->attributes) &&
            !array_key_exists('SurName', $this->attributes)) {
            throw new \Exception('CompanyName or SurName are required fields');
        }

        $params = $this->toArray();

        $response = Client::sendRequest('debtor', 'add', $params);

        $this->setRawAttributes($response['debtor']);

        return $this;
    }

    /**
     * @param array $params
     *
     * @return $this
     * @throws \Exception
     */
    public function update(array $params = []): self
    {
        if (
            (!array_key_exists('CompanyName', $params) && !isset($this->attributes['CompanyName'])) &&
            (!array_key_exists('SurName', $params) && !isset($this->attributes['SurName']))) {
            throw new \Exception('CompanyName or SurName are required fields');
        }

        $this->fill($params);

        $params = $this->toArray();

        $response = Client::sendRequest('debtor', 'edit', $params);

        $this->setRawAttributes($response['debtor']);

        return $this;
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

        $debtors = [];
        $response = $response['debtors'];

        foreach ($response as $debtor_object) {
            $debtor = Debtor::show($debtor_object['Identifier']);
            $debtors[] = $debtor;
        }

        return new Collection($debtors);
    }
}