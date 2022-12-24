<?php

use Tnpdigital\Cardinal\Hostfact\Models\Debtor;
use Tnpdigital\Cardinal\Hostfact\Tests\TestCase;

class DebtorTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testItCanGetADebtor()
    {
        $debtor = Debtor::show(1);

        $this->assertIsObject($debtor);
        $this->assertIsArray($debtor->CustomFields);
    }

    /**
     * @throws \Exception
     */
    public function testItCanListAllDebtors()
    {
        $debtors = Debtor::list();

        $this->assertIsObject($debtors);
    }

    /**
     * @throws \Exception
     */
    public function testItCanListAllDebtorsWithExtraParams()
    {
        $debtors = Debtor::list([ 'searchat' => 'EmailAddress', 'searchfor' => 'info@tnpdigital.nl']);

        $this->assertIsObject($debtors);
        $this->assertEquals($debtors->count(), 1);
    }

    /**
     * @throws \Exception
     */
    public function testItCanEditADebtor()
    {
        $debtor = Debtor::show(6);

        $return_value = $debtor->edit(['Website' => 'https://tnpdigital.nl']);

        $this->assertIsObject($return_value);
    }
}
