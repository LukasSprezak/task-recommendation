<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\RecommendedFilterService;
use PHPUnit\Framework\TestCase;

use function array_filter;
use function mb_strlen;
use function str_word_count;

class RecommendedFilterServiceTest extends TestCase
{
    private RecommendedFilterService $recommendedFilterService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recommendedFilterService = new RecommendedFilterService();
    }

    public function testShouldMoviesLetterWAndOddNumChar(): void
    {
        $movies = $this->recommendedFilterService->getElementAfterSelectedLetterAndItIsEvenOrOdd(
            array: ["Władca Pierścieni: Dwie wieże"], selectLetter: "W", isEven: false
        );
        self::assertSame(expected: 29, actual: mb_strlen($movies[0]));
    }

    public function testShouldMoviesLetterWAndEvenNumChar(): void
    {
        $movies = $this->recommendedFilterService->getElementAfterSelectedLetterAndItIsEvenOrOdd(
            array: ["Władca Pierścieni: Drużyna Pierścienia"], selectLetter: "W", isEven: true
        );
        self::assertSame(expected: 38, actual: mb_strlen($movies[0]));
    }

    public function testShouldMoviesWithAnIncorrectLetterUmlautAndEvenNumChar1(): void
    {
        $movies = $this->recommendedFilterService->getElementAfterSelectedLetterAndItIsEvenOrOdd(
            array: ["üładca Pierścieni: Drużyna Pierścienia"], selectLetter: "ü", isEven: true
        );
        self::assertEmpty($movies);
    }

    public function testShouldTitleMoreThanOneWord(): void
    {
        $movies = $this->recommendedFilterService->getElementWithSelectedNumberWords(array: ["Władca Pierścieni: Drużyna Pierścienia", "Incepcja"], numberWords: 1);
        self::assertCount(expectedCount: 1, haystack: $movies);
    }

    public function testShouldReturnNoValueWhenMoviesAreOnOneWord(): void
    {
        $movies = $this->recommendedFilterService->getElementWithSelectedNumberWords(array: ["Siedem", "Incepcja","Nietykalni"], numberWords: 1);
        self::assertCount(expectedCount: 0, haystack: $movies);
    }

    public function testShouldIncorrectWordCountingIfThereIsNoAdditionalProtectionForPolishCharacters(): void
    {
        $movies = ["Władca"];
        self::assertCount(expectedCount: 1, haystack: array_filter(array: $movies, callback: static function ($element) {
            return 1 < str_word_count(string: $element);
        }));
    }
}
