<?php

namespace App\Service;

use App\Providers\CustomPhoneNumberProvider;
use Faker\Factory as FakerFactory;
use Faker\Provider\ru_RU\Address as RuAddressProvider;
use Faker\Provider\ru_RU\Person as RuPersonProvider;
use Faker\Provider\en_US\Address as EnAddressProvider;
use Faker\Provider\en_US\Person as EnPersonProvider;
use Faker\Provider\uk_UA\Address as UkAddressProvider;
use Faker\Provider\uk_UA\Person as UkPersonProvider;

class GeneratorService
{
    protected $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    public function generateUserData(?int $count = 15, ?string $region = 'RU', ?int $seed = 0,
                                     float|int|null $countErrors = 0): array
    {
        $this->faker->seed($seed);
        $userData = [];
        for ($i = 0; $i < $count; $i++) {
            $this->setRegionProvider($region);

            $userData[] = $this->introduceError([
                'uuid' => $this->faker->uuid,
                'name' => $this->faker->name,
                'address' => $this->faker->address,
                'phone_number' => $this->faker->customPhonesNumbers($region),
            ], $countErrors);
        }

        return $userData;
    }

    protected function setRegionProvider($region)
    {
        switch ($region) {
            case 'RU':
                $this->faker->addProvider(new RuAddressProvider($this->faker));
                $this->faker->addProvider(new RuPersonProvider($this->faker));
                $this->faker->addProvider(new CustomPhoneNumberProvider($this->faker));
                break;
            case 'EN':
                $this->faker->addProvider(new EnAddressProvider($this->faker));
                $this->faker->addProvider(new EnPersonProvider($this->faker));
                $this->faker->addProvider(new CustomPhoneNumberProvider($this->faker));
                break;
            case 'UA':
                $this->faker->addProvider(new UkAddressProvider($this->faker));
                $this->faker->addProvider(new UkPersonProvider($this->faker));
                $this->faker->addProvider(new CustomPhoneNumberProvider($this->faker));
                break;
        }
    }

    function introduceError(array $data, int|float $countErrors): array
    {
        $countErrors = $countErrors * 100;
        $countErrorsMade = 0;

        if($countErrors != 0){
            foreach ($data as $key => &$value) {
                if ($key === 'uuid') {
                    continue;
                }
                if (is_float($countErrors)) {
                    $this->getAdditionalError($countErrors);
                }

                $value = $this->creatingErrors($value, $countErrors, $countErrorsMade);
            }
        }

        return $data;
    }

    public function creatingErrors(string $value, float $countErrors, int &$countErrorsMade): string
    {
        $stringLength = mb_strlen($value);

        for ($i = 0; $i < $stringLength && $countErrorsMade <= $countErrors; $i++) {
            $countErrorsMade++;
            $errorType = mt_rand(1, 3);
            if ($errorType == 1 && $stringLength < (mb_strlen($value) / 3)) {
                $value = $this->deleteCharacter($value);
            } elseif ($errorType == 2) {
                $value = $this->swapCharacters($value);
            } elseif ($errorType == 3) {
                $value = $this->replaceCharacter($value);
            }
        }

        return $value;
    }

    /**
     * Deleting a character
     */
    public function deleteCharacter(string $value): string
    {
        $errorPosition = mt_rand(0, mb_strlen($value, 'UTF-8') - 1);
        $chars = preg_split('//u', $value, null, PREG_SPLIT_NO_EMPTY);
        array_splice($chars, $errorPosition, 1);

        return implode('', $chars);
    }

    /**
     * Rearranging characters
     */
    public function swapCharacters(string $value): string
    {
        $errorPosition = mt_rand(0, mb_strlen($value, 'UTF-8') - 2);
        $chars = preg_split('//u', $value, null, PREG_SPLIT_NO_EMPTY);
        $temp = $chars[$errorPosition];
        $chars[$errorPosition] = $chars[$errorPosition + 1];
        $chars[$errorPosition + 1] = $temp;

        return implode('', $chars);
    }

    /**
     * Replacing a character
     */
    public function replaceCharacter(string $value): string
    {
        $errorPosition = mt_rand(0, mb_strlen($value, 'UTF-8') - 1);
        $randomRussianLetter = mb_chr(mt_rand(1072, 1103), 'UTF-8');

        return mb_substr($value, 0, $errorPosition) . $randomRussianLetter . mb_substr($value, $errorPosition + 1);
    }

    public function getAdditionalError(int|float $countErrors): int
    {
        $randomNumber = mt_rand(0, 1);
        if ($randomNumber == 1) {
            $countErrors = $countErrors + 0.5;
        } else {
            $countErrors = $countErrors - 0.5;
        }

        return $countErrors;
    }
}
