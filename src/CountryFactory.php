<?php

namespace rocketfellows\ISOStandard3166Factory;

use arslanimamutdinov\ISOStandard3166\Country;
use arslanimamutdinov\ISOStandard3166\ISO3166;
use rocketfellows\ISOStandard3166Factory\exceptions\EmptyCountryCodeException;
use rocketfellows\ISOStandard3166Factory\exceptions\UnknownCountryCodeException;
use rocketfellows\ISOStandard3166Validation\validators\Alpha2;
use rocketfellows\ISOStandard3166Validation\validators\Alpha3;
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
