<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

use Carbon\Carbon;
use Tnpdigital\Cardinal\Hostfact\Client;
use Illuminate\Database\Eloquent\Collection;
use Tnpdigital\Cardinal\Hostfact\Contracts\ModelContract;

/**
 * @property int Identifier
 * @property string InvoiceCode
 * @property int Debtor
 * @property string DebtorCode
 * @property int Status
 * @property int SubStatus
 * @property string Date
 * @property int Term
 * @property float AmountExcl
 * @property float AmountTax
 * @property float AmountIncl
 * @property int TaxRate
 */
class Invoice extends Model implements ModelContract
{

    /**
     * @param array $params
     *
     * @return $this
     * @throws \Exception
     */
    public static function add(array $params = []): static
    {
        if (!array_key_exists('Debtor', $params) && !array_key_exists('DebtorCode', $params)) {
            throw new \Exception('Debtor or DebtorCode are required fields');
        }

        if (!array_key_exists('InvoiceLines', $params)) {
            throw new \Exception('InvoiceLines is a required field');
        }

        $response = Client::sendRequest('invoice', 'add', $params);

        $invoice = new static;
        $invoice->setRawAttributes($response['invoice']);

        return $invoice;
    }

    /**
     * @param array $params
     *
     * @return $this
     * @throws \Exception
     */
    public function edit(array $params = []): self
    {
        if (
            (!array_key_exists('Identifier', $params) && !isset($this->Identifier)) &&
            (!array_key_exists('InvoiceCode', $params) && !isset($this->InvoiceCode))) {
            throw new \Exception('Identifier or InvoiceCode are required fields');
        }

        $params['Identifier'] = $this->Identifier;

        Client::sendRequest('invoice', 'edit', $params);

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
        $response = Client::sendRequest('invoice', 'show', [
            'Identifier' => $Identifier
        ]);

        $debtor = new static;
        $debtor->setRawAttributes($response['invoice']);

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
        $response = Client::sendRequest('invoice', 'list', [
                'limit' => 1000
            ] + $params);

        $invoices = [];
        $response = $response['invoices'];

        foreach ($response as $invoice_object) {
            $invoice = Invoice::show($invoice_object['Identifier']);
            $invoices[] = $invoice;
        }

        return new Collection($invoices);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function delete(): bool
    {
        Client::sendRequest('invoice', 'delete', [
            'Identifier' => $this->Identifier
        ]);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function credit(): bool
    {
        Client::sendRequest('invoice', 'credit', [
            'Identifier' => $this->Identifier
        ]);

        return true;
    }

    /**
     * @param float               $amount_paid
     * @param \Carbon\Carbon|null $pay_date
     *
     * @return bool
     * @throws \Exception
     */
    public function partPayment(float $amount_paid, Carbon $pay_date = null): bool
    {
        $params = [];

        if(!is_null($pay_date)) {
            $params['PayDate'] = $pay_date->format('Y-m-d');
        }

        $params['Identifier'] = $this->Identifier;
        $params['AmountPaid'] = $amount_paid;

        Client::sendRequest('invoice', 'partpayment', $params);

        return true;
    }

    /**
     * @param \Carbon\Carbon|null $pay_date
     *
     * @return bool
     * @throws \Exception
     */
    public function markAsPaid(Carbon $pay_date = null): bool
    {
        $params = [];

        if(!is_null($pay_date)) {
            $params['PayDate'] = $pay_date->format('Y-m-d');
        }

        $params['Identifier'] = $this->Identifier;

        Client::sendRequest('invoice', 'markaspaid', $params);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function markAsUnpaid(): bool
    {
        Client::sendRequest('invoice', 'markasunpaid', ['Identifier' => $this->Identifier]);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function sendByEmail(): bool
    {
        Client::sendRequest('invoice', 'sendbymail', ['Identifier' => $this->Identifier]);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function sendReminderByEmail(): bool
    {
        Client::sendRequest('invoice', 'sendreminderbyemail', ['Identifier' => $this->Identifier]);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function sendSummationByEmail(): bool
    {
        Client::sendRequest('invoice', 'sendsummationbyemail', ['Identifier' => $this->Identifier]);

        return true;
    }
}