<?php

namespace App\Providers;

use Faker\Provider\Base;

class CustomPhoneNumberProvider extends Base
{
    public function customPhonesNumbers($region): ?string
    {
        return match ($region) {
            'RU' => $this->customRuPhoneNumber(),
            'EN' => $this->customEnPhoneNumber(),
            'UA' => $this->customUaPhoneNumber(),
            default => null,
        };
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
