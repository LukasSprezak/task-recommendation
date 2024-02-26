<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\NumberMoreThanOneException;
use App\Exception\NumberMustNotGreaterThanMaxElementException;
use Random\Randomizer;

use function array_filter;
use function array_map;
use function count;
use function mb_strlen;
use function strcasecmp;
use function str_word_count;

class RecommendedFilterService
{
    private const POLISH_LATIN_EXTEND = 'ąęóżźćńłśĄĘÓŻŹĆŃŁŚ0123456789';

    /**
     * @throws NumberMustNotGreaterThanMaxElementException
     * @throws NumberMoreThanOneException
     */
    public function randomSelectElements(array $array, int $number): array
    {
        if (! empty($array)) {

            if (1 > $number) {
                throw new NumberMoreThanOneException($number);
            }

            if (count($array) < $number) {
                throw new NumberMustNotGreaterThanMaxElementException($number);
            }

            $keys = (new Randomizer())->pickArrayKeys(array: $array, num: $number);

            return array_map(
                callback: static fn (string $key): string => $array[$key],
                array: $keys
            );
        }

        return $array;
    }

    public function getElementAfterSelectedLetterAndItIsEvenOrOdd(array $array, string $selectLetter, bool $isEven): array
    {
        return array_filter(array: $array, callback: static function ($element) use ($selectLetter, $isEven) {
            return strcasecmp(string1: $element[0] ?? '', string2: $selectLetter) === 0 && mb_strlen(string: $element) % 2 === ($isEven ? 0 : 1);
        });
    }

    public function getElementWithSelectedNumberWords(array $array, int $numberWords): array
    {
        return array_filter(array: $array, callback: static function ($element) use ($numberWords) {
            return $numberWords < str_word_count(string: $element, characters: self::POLISH_LATIN_EXTEND);
        });
    }

    public function kNearestNeighbours(): void
    {
        // @TODO if I had more data on mvoies I would consider prediction algorithms for recommendations k nearest neighbours and maybe Pearson correlation method.
    }
}
