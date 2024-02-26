<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Data\Movie;
use App\Exception\NumberMoreThanOneException;
use App\Exception\NumberMustNotGreaterThanMaxElementException;
use App\Service\RecommendedFilterService;
use PHPUnit\Framework\TestCase;

use function array_values;

class RecommendedFilterServiceTest extends TestCase
{
    private RecommendedFilterService $recommendedFilterService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recommendedFilterService = new RecommendedFilterService();
    }

    public function testFilterMoviesLetterWAndEvenLength(): void
    {
        $movies = Movie::getAll();
        $moviesExpected = [
            'Whiplash',
            'Wyspa tajemnic',
            'Władca Pierścieni: Drużyna Pierścienia',
        ];

        $filtered = $this->recommendedFilterService->getElementAfterSelectedLetterAndItIsEvenOrOdd(array: $movies, selectLetter: 'W', isEven: true);

        //then
        $this->assertCount(expectedCount: 3, haystack: $filtered);
        $this->assertEquals(expected: $moviesExpected, actual: array_values($filtered));
    }

    public function testFilterMoviesLetterWOddLength(): void
    {
        //given
        $movies = Movie::getAll();
        $moviesExpected = [
            'Chłopaki nie płaczą',
            'Człowiek z blizną',
            'Casino Royale',
            'Czarnobyl',
        ];

        //when
        $filtered = $this->recommendedFilterService->getElementAfterSelectedLetterAndItIsEvenOrOdd(array: $movies, selectLetter: 'C', isEven: false);

        //then
        $this->assertCount(expectedCount: 4, haystack: $filtered);
        $this->assertContains(needle: 'Chłopaki nie płaczą', haystack: $filtered);
        $this->assertEquals(expected: $moviesExpected, actual: array_values($filtered));
    }

    public function testFilterTitleMoreThanOneWord(): void
    {
        //given
        $movies = Movie::getAll();
        $moviesExpected = [
            'Pulp Fiction',
            'Skazani na Shawshank',
            'Dwunastu gniewnych ludzi',
            'Ojciec chrzestny',
            'Leon zawodowiec',
            'Władca Pierścieni: Powrót króla',
            'Fight Club',
            'Forrest Gump',
            'Chłopaki nie płaczą',
            'Człowiek z blizną',
            'Doktor Strange',
            'Szeregowiec Ryan',
            'Lot nad kukułczym gniazdem',
            'Wielki Gatsby',
            'Avengers: Wojna bez granic',
            'Życie jest piękne',
            'Pożegnanie z Afryką',
            'Milczenie owiec',
            'Dzień świra',
            'Blade Runner',
            'Król Lew',
            'La La Land',
            'Wyspa tajemnic',
            'American Beauty',
            'Szósty zmysł',
            'Gwiezdne wojny: Nowa nadzieja',
            'Mroczny Rycerz',
            'Władca Pierścieni: Drużyna Pierścienia',
            'Harry Potter i Kamień Filozoficzny',
            'Green Mile',
            'Mad Max: Na drodze gniewu',
            'Terminator 2: Dzień sądu',
            'Piraci z Karaibów: Klątwa Czarnej Perły',
            'Truman Show',
            'Skazany na bluesa',
            'Gran Torino',
            'Mroczna wieża',
            'Casino Royale',
            'Piękny umysł',
            'Władca Pierścieni: Dwie wieże',
            'Zielona mila',
            'Requiem dla snu',
            'Forest Gump',
            'Requiem dla snu',
            'Milczenie owiec',
            'Breaking Bad',
            'Nagi instynkt',
            'Igrzyska śmierci',
            'Siedem dusz',
            'Dzień świra',
            'Pan życia i śmierci',
            'Hobbit: Niezwykła podróż',
            'Pachnidło: Historia mordercy',
            'Wielki Gatsby',
            'Sin City',
            'Przeminęło z wiatrem',
            'Królowa śniegu'
        ];

        //when
        $filtered = $this->recommendedFilterService->getElementWithSelectedNumberWords(array:$movies, numberWords: 1);

        //then
        $this->assertCount(expectedCount: 57, haystack: $filtered);
        $this->assertEquals(expected: $moviesExpected, actual: array_values($filtered));
    }

    public function testForPositivelySendingThreeRandomMovies(): void
    {
        //given
        $movies = Movie::getAll();

        //when
        $filtered = $this->recommendedFilterService->getElementWithSelectedNumberWords(array:$movies, numberWords: 3);
        $randomMovies = array_unique($filtered);

        //then
        $this->assertCount(expectedCount: count($filtered), haystack: $randomMovies);
    }

    public function testShouldThrowAnExceptionIfWeGiveValueOfZeroForThreeRandomMovies(): void
    {
        //expect
        $this->expectException(NumberMoreThanOneException::class);
        $this->expectExceptionMessage('The list of films must not be less than one. "0"');

        //given
        $movies = Movie::getAll();
        $this->recommendedFilterService->randomSelectElements(array: $movies, number: 0);
    }

    public function testShouldThrowAnExceptionIfWeGiveValueGreaterThanThereAreElementsInListInThreeRandomMovies(): void
    {
        //expect
        $this->expectException(NumberMustNotGreaterThanMaxElementException::class);
        $this->expectExceptionMessage('The number of films selected may not exceed the maximum number of elements. "100"');

        //given
        $movies = Movie::getAll();
        $this->recommendedFilterService->randomSelectElements(array: $movies, number: 100);
    }
}
