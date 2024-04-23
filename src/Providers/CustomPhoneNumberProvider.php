<?php

namespace App\Providers;

use Faker\Provider\Base;

class CustomPhoneNumberProvider extends Base
{
    public function customPhonesNumbers($region)
    {
        switch ($region) {
            case 'RU':
                return $this->customRuPhoneNumber();
            case 'EN':
                return $this->customEnPhoneNumber();
            case 'UA':
                return $this->customUaPhoneNumber();
        }
    }

    public function customRuPhoneNumber(): string
    {
        $randomNumber = mt_rand(0, 2);

        return match($randomNumber) {
            0 => $this->numerify('8 (9##) ###-##-##'),
            1 => $this->numerify('(9##) ###-##-##'),
            2 => $this->numerify('+7 (9##) ####-###'),
            default => '',
        };
    }

    public function customEnPhoneNumber(): string
    {
        $randomNumber = mt_rand(0, 2);

        return match($randomNumber) {
            0 => $this->numerify('+44 (7###) ##-##-##'),
            1 => $this->numerify('(020) 7###-####'),
            2 => $this->numerify('(028) 95##-####'),
            default => '',
        };
    }

    public function customUaPhoneNumber(): string
    {
        return $this->numerify('+380 (##) ###-##-##');
    }
}
