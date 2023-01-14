<?php

namespace rocketfellows\ISOStandard3166Factory\tests\unit;

use PHPUnit\Framework\TestCase;

class CountryFactoryTest extends TestCase
{
    /**
     * @var CountryFactory
     */
    private $countryFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->countryFactory = new CountryFactory();
    }
}
