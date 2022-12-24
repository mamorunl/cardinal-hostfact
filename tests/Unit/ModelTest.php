<?php

namespace Tnpdigital\Cardinal\Hostfact\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tnpdigital\Cardinal\Hostfact\Models\Debtor;

class ModelTest extends TestCase
{
    public function testItFillsAttributes()
    {
        $debtor = new Debtor();
        $debtor->setRawAttributes([ 'id' => 1 ]);

        $this->assertEquals($debtor->id, 1);
    }

    public function testItReturnsNullOnUnknownAttribute()
    {
        $debtor = new Debtor();
        $debtor->setRawAttributes([ 'id' => 1 ]);

        $this->assertNull($debtor->unknown);
    }

    public function testItSetsAnAttribute()
    {
        $debtor = new Debtor();
        $debtor->attribute = "Yes";

        $this->assertEquals($debtor->attribute, "Yes");
    }
}