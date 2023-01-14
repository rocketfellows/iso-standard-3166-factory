<?php

namespace rocketfellows\ISOStandard3166Factory\tests\unit;

use arslanimamutdinov\ISOStandard3166\Country;
use PHPUnit\Framework\TestCase;
use rocketfellows\ISOStandard3166Factory\CountryFactory;
use rocketfellows\ISOStandard3166Factory\exceptions\EmptyCountryCodeException;
use rocketfellows\ISOStandard3166Factory\exceptions\UnknownCountryCodeException;

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

    /**
     * @dataProvider getSuccessCreateCountryByCodeProvidedData
     */
    public function testSuccessCreateCountryByCode(string $code, Country $expectedCountry): void
    {
        $this->assertEquals($expectedCountry, $this->countryFactory->createByCode($code));
    }

    public function getSuccessCreateCountryByCodeProvidedData(): array
    {
        return [
            'alpha2 country code in upper case' => [
                'code' => 'AT',
                'expectedCountry' => new Country('Austria', 'AT', 'AUT', '040'),
            ],
            'alpha2 country code in lower case' => [
                'code' => 'at',
                'expectedCountry' => new Country('Austria', 'AT', 'AUT', '040'),
            ],
            'alpha2 country code in lower and upper case' => [
                'code' => 'aT',
                'expectedCountry' => new Country('Austria', 'AT', 'AUT', '040'),
            ],
            'alpha3 country code in upper case' => [
                'code' => 'AUT',
                'expectedCountry' => new Country('Austria', 'AT', 'AUT', '040'),
            ],
            'alpha3 country code in lower case' => [
                'code' => 'aut',
                'expectedCountry' => new Country('Austria', 'AT', 'AUT', '040'),
            ],
            'alpha3 country code in lower and upper case' => [
                'code' => 'AuT',
                'expectedCountry' => new Country('Austria', 'AT', 'AUT', '040'),
            ],
            'numeric code' => [
                'code' => '040',
                'expectedCountry' => new Country('Austria', 'AT', 'AUT', '040'),
            ],
        ];
    }

    /**
     * @dataProvider getHandleCreateCountryByInvalidCodeProvidedData
     */
    public function testHandleCreateCountryByInvalidCode(string $code, string $expectedExceptionClass): void
    {
        $this->expectException($expectedExceptionClass);

        $this->countryFactory->createByCode($code);
    }

    public function getHandleCreateCountryByInvalidCodeProvidedData(): array
    {
        return [
            'empty code' => [
                'code' => '',
                'expectedExceptionClass' => EmptyCountryCodeException::class,
            ],
            'unknown alpha2 code' => [
                'code' => 'BU',
                'expectedExceptionClass' => UnknownCountryCodeException::class,
            ],
            'unknown alpha3 code' => [
                'code' => 'BUE',
                'expectedExceptionClass' => UnknownCountryCodeException::class,
            ],
            'unknown numeric code' => [
                'code' => '111',
                'expectedExceptionClass' => UnknownCountryCodeException::class,
            ],
        ];
    }
}
