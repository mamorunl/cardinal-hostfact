<?php

namespace Tnpdigital\Cardinal\Hostfact\Models;

use Carbon\CarbonInterface;
use Tnpdigital\Cardinal\Hostfact\Client;
use Illuminate\Database\Eloquent\Collection;

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
class Invoice extends Model
{
    protected $invoiceLines;

    /**
     * @param array $params
     *
     * @return $this
     * @throws \Exception
     */
    public function create(): self
    {
        if(isset($this->Identifier)) {
            throw new \Exception('Identifier already set. Use UPDATE instead');
        }

        if (!array_key_exists('Debtor', $this->attributes) && !array_key_exists('DebtorCode', $this->attributes)) {
            throw new \Exception('Debtor or DebtorCode are required fields');
        }

        $params = $this->generateParams();

        $response = Client::sendRequest('invoice', 'add', $params);

        $this->setRawAttributes($response['invoice']);
        unset($this->invoiceLines);

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
            (!array_key_exists('Identifier', $params) && !isset($this->attributes['Identifier'])) &&
            (!array_key_exists('InvoiceCode', $params) && !isset($this->attributes['InvoiceCode']))) {
            throw new \Exception('Identifier or InvoiceCode are required fields');
        }

        $this->fill($params);

        $params = $this->generateParams();

        $response = Client::sendRequest('invoice', 'edit', $params);

        $this->setRawAttributes($response['invoice']);
        unset($this->invoiceLines);

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
    public function partPayment(float $amount_paid, CarbonInterface $pay_date = null): bool
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
    public function markAsPaid(CarbonInterface $pay_date = null): bool
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

    /**
     * @return array
     * @throws \Exception
     */
    public function download(): array
    {
        $response = Client::sendRequest('invoice', 'download', ['Identifier' => $this->Identifier]);

        return $response['invoice'];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function block(): bool
    {
        Client::sendRequest('invoice', 'block', ['Identifier' => $this->Identifier]);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function unblock(): bool
    {
        Client::sendRequest('invoice', 'unblock', ['Identifier' => $this->Identifier]);

        return true;
    }

    /**
     * @param \Carbon\Carbon $schedule_at
     *
     * @return bool
     * @throws \Exception
     */
    public function schedule(CarbonInterface $schedule_at): bool
    {
        Client::sendRequest('invoice', 'schedule', [
            'Identifier' => $this->Identifier,
            'ScheduledAt' => $schedule_at->format('Y-m-d H:i:s')
        ]);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function cancelSchedule(): bool
    {
        Client::sendRequest('invoice', 'cancelschedule', ['Identifier' => $this->Identifier]);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function paymentProcessPause(): bool
    {
        Client::sendRequest('invoice', 'paymentprocesspause', ['Identifier' => $this->Identifier]);

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function paymentProcessReactivate(): bool
    {
        Client::sendRequest('invoice', 'paymentprocessreactivate', ['Identifier' => $this->Identifier]);

        return true;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function invoiceLines(): \Illuminate\Support\Collection
    {
        if($this->invoiceLines instanceof \Illuminate\Support\Collection) {
            return $this->invoiceLines;
        }

        if(empty($this->attributes['InvoiceLines'])) {
            return $this->invoiceLines = new \Illuminate\Support\Collection();
        }

        $invoice_lines = [];
        foreach ($this->attributes['InvoiceLines'] as $invoice_line) {
            $line = new InvoiceLine();
            $line->fill($invoice_line);

            $invoice_lines[] = $line;
        }

        $this->invoiceLines = collect($invoice_lines);

        return $this->invoiceLines;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function generateParams(): array
    {
        if (!$this->invoiceLines()
                  ->count()) {
            throw new \Exception('InvoiceLines is a required field');
        }

        $invoice_lines = [];

        foreach ($this->invoiceLines() as $invoice_line) {
            $invoice_lines[] = $invoice_line->toArray();
        }

        $params = $this->toArray();

        $params['InvoiceLines'] = $invoice_lines;

        return $params;
    }
}