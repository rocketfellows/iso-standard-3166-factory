<?php

namespace rocketfellows\ISOStandard3166Factory;

use arslanimamutdinov\ISOStandard3166\Country;
use arslanimamutdinov\ISOStandard3166\ISO3166;
use rocketfellows\ISOStandard3166Factory\exceptions\EmptyCountryCodeException;
use rocketfellows\ISOStandard3166Factory\exceptions\EmptyCountryNameException;
use rocketfellows\ISOStandard3166Factory\exceptions\UnknownCountryCodeException;
use rocketfellows\ISOStandard3166Factory\exceptions\UnknownCountryNameException;
use rocketfellows\ISOStandard3166Validation\validators\Alpha2;
use rocketfellows\ISOStandard3166Validation\validators\Alpha3;
use rocketfellows\ISOStandard3166Validation\validators\Name;
use rocketfellows\ISOStandard3166Validation\validators\NumericCode;

class CountryFactory
{
    /**
     * @throws EmptyCountryCodeException
     * @throws UnknownCountryCodeException
     */
    public function createByCode(string $code): Country
    {
        $this->validateCode($code);

        $countryCode = strtoupper($code);
        $country = ISO3166::getByAlpha2($countryCode) ??
            ISO3166::getByAlpha3($countryCode) ??
            ISO3166::getByNumericCode($countryCode);

        if (!$country instanceof Country) {
            throw new UnknownCountryCodeException();
        }

        return $country;
    }

    /**
     * @throws UnknownCountryNameException
     * @throws EmptyCountryNameException
     */
    public function createByName(string $countryName): Country
    {
        if (empty($countryName)) {
            throw new EmptyCountryNameException();
        }

        if (!Name::create()->isValid($countryName)) {
            throw new UnknownCountryNameException();
        }

        $country = ISO3166::getAllByNames([$countryName])[0] ?? null;

        if (!$country instanceof Country) {
            throw new UnknownCountryNameException();
        }

        return $country;
    }

    /**
     * @throws UnknownCountryCodeException
     * @throws EmptyCountryCodeException
     */
    private function validateCode(string $code): void
    {
        if (empty($code)) {
            throw new EmptyCountryCodeException();
        }

        if (!Alpha2::create()->isValid($code) && !Alpha3::create()->isValid($code) && !NumericCode::create()->isValid($code)) {
            throw new UnknownCountryCodeException();
        }
    }
}
