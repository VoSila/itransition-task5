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

    public function generateUserData(?int $count = 10, ?string $region = 'RU', ?int $seed = 1,
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
            ], $countErrors, $region);
        }

        return $userData;
    }

    protected function setRegionProvider(string $region): void
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

    public function introduceError(array $data, int|float $countErrors, string $region): array
    {
        if ($countErrors === 0) {
            return $data;
        }

        $uuidValue = $data['uuid'];
        unset($data['uuid']);
        $string = implode(";", $data);
        $stringWithErrors = $this->injectErrors($string, $countErrors, $region);
        $dataArray = explode(";", $stringWithErrors);
        $data = array_combine(array_keys($data), $dataArray);
        $data['uuid'] = $uuidValue;

        return $data;
    }

    private function injectErrors(string $string, int|float $countErrors, string $region): string
    {
        $countErrorsMade = 0;

        if (is_float($countErrors)) {
            $countErrors = $this->getRandomBoolean($countErrors);
        }

        return $this->creatingErrors($string, $countErrors, $countErrorsMade, $region);
    }

    public function creatingErrors(string $value, float $countErrors, int &$countErrorsMade, string $region): string
    {
        $stringLength = mb_strlen($value);
        for ($i = 0; $i < $countErrors && $countErrorsMade <= $countErrors; $i++) {
            $countErrorsMade++;
            $errorType = mt_rand(1, 3);
            switch ($errorType) {
                case 1:
                    if ($stringLength < (mb_strlen($value) / 3)) {
                        $value = $this->deleteCharacter($value);
                    }
                    break;
                case 2:
                    $value = $this->swapCharacters($value);
                    break;
                case 3:
                    $value = $this->replaceCharacter($value, $region);
                    break;
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

        while ($chars[$errorPosition] === ';') {
            $errorPosition = mt_rand(0, mb_strlen($value, 'UTF-8') - 1);
        }

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
    public function replaceCharacter(string $value, string $region): string
    {
        $errorPosition = mt_rand(0, mb_strlen($value, 'UTF-8') - 1);
        if($region != 'EN'){
            $randomRussianLetter = mb_chr(mt_rand(1040, 1103), 'UTF-8');
        }else{
            $randomRussianLetter = mb_chr(mt_rand(65, 122), 'UTF-8');
        }

        while (mb_substr($value, $errorPosition, 1) === ';') {
            $errorPosition = mt_rand(0, mb_strlen($value, 'UTF-8') - 1);
        }

        return mb_substr($value, 0, $errorPosition) . $randomRussianLetter . mb_substr($value, $errorPosition + 1);
    }

    public function getRandomBoolean(float $countErrors): int
    {
        $randomNumber = 0;

        $fractionalPart = fmod($countErrors, 1);
        if ($fractionalPart === 0.25 || $fractionalPart === 0.75) {
            $randomNumber = mt_rand(1, 4);
        } elseif ($fractionalPart === 0.5) {
            $randomNumber = mt_rand(1, 2);
        }
        if ($randomNumber === 1) {

            return ceil($countErrors);
        } else {
            return floor($countErrors);
        }
    }
}
