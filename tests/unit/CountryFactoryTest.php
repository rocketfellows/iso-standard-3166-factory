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
     * @dataProvider getSuccessCreateCountryByNameProvidedData
     */
    public function testSuccessCreateCountryByName(string $countryName, Country $expectedCountry): void
    {
        $this->assertEqualsCountry($expectedCountry, $this->countryFactory->createByName($countryName));
    }

    public function getSuccessCreateCountryByNameProvidedData(): array
    {
        return [
            'Switzerland country name' => [
                'countryName' => 'Switzerland',
                'expectedCountry' => new Country('Switzerland', 'CH', 'CHE', '756'),
            ],
            'Syrian Arab Republic country name' => [
                'countryName' => 'Syrian Arab Republic',
                'expectedCountry' => new Country('Syrian Arab Republic', 'SY', 'SYR', '760'),
            ],
            'Taiwan, Province of China country name' => [
                'countryName' => 'Taiwan, Province of China',
                'expectedCountry' => new Country('Taiwan, Province of China', 'TW', 'TWN', '158'),
            ],
            'Paraguay country name' => [
                'countryName' => 'Paraguay',
                'expectedCountry' => new Country('Paraguay', 'PY', 'PRY', '600'),
            ],
            'Montserrat country name' => [
                'countryName' => 'Montserrat',
                'expectedCountry' => new Country('Montserrat', 'MS', 'MSR', '500'),
            ],
        ];
    }

    /**
     * @dataProvider getHandleCreateCountryByInvalidNameProvidedData
     */
    public function testHandleCreateCountryByInvalidName(string $countryName, string $expectedExceptionClass): void
    {
        $this->expectException($expectedExceptionClass);

        $this->countryFactory->createByName($countryName);
    }

    public function getHandleCreateCountryByInvalidNameProvidedData(): array
    {
        return [
            'unknown country name' => [
                'countryName' => 'Foo bar',
                'expectedExceptionClass' => UnknownCountryNameException::class,
            ],
            'invalid Switzerland country name' => [
                'countryName' => 'Switerland',
                'expectedExceptionClass' => UnknownCountryNameException::class,
            ],
        ];
    }

    /**
     * @dataProvider getSuccessCreateCountryByCodeProvidedData
     */
    public function testSuccessCreateCountryByCode(string $code, Country $expectedCountry): void
    {
        $this->assertEqualsCountry($expectedCountry, $this->countryFactory->createByCode($code));
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

    private function assertEqualsCountry(Country $expectedCountry, Country $actualCountry): void
    {
        $this->assertInstanceOf(Country::class, $actualCountry);
        $this->assertEquals($expectedCountry, $actualCountry);
    }
}
