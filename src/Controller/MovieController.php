<?php

declare(strict_types=1);

namespace App\Controller;

use App\Data\Movie;
use App\Enum\RecommendedFilterMovieEnum;
use App\Exception\NumberMoreThanOneException;
use App\Exception\NumberMustNotGreaterThanMaxElementException;
use App\Provider\ResponseProvider;
use App\Service\RecommendedFilterService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/api/movies', name: 'api_movie_')]
final class MovieController extends AbstractBaseController
{
    private const SELECT_LETTER = 'W';

    public function __construct(
        private readonly RecommendedFilterService $recommendedFilterService,
    ) {
    }

    #[Route(path: '', name: 'get_all', methods: [Request::METHOD_GET])]
    public function getAll(): JsonResponse
    {
        $movies = Movie::getAll();

        return $this->json(
            responseProvider: new ResponseProvider(
                status: Response::HTTP_OK,
                message: 'All movies were successfully displayed.',
                data: ['movies' => $movies]
            )
        );
    }

    /**
     * @throws NumberMoreThanOneException
     * @throws NumberMustNotGreaterThanMaxElementException
     */
    #[Route(path: '/recommended_filter/{parameter}', name: 'recommended_filter', methods: [Request::METHOD_GET])]
    public function recommendedFilter(Request $request): JsonResponse
    {
        $value = $request->get(key: 'parameter');

        return $this->json(
            responseProvider: new ResponseProvider(
                status: Response::HTTP_OK,
                message: 'All movies were successfully displayed.',
                data: ['movies' => $this->listOfRecommendedFilter($value)],
            )
        );
    }

    /**
     * @throws NumberMustNotGreaterThanMaxElementException
     * @throws NumberMoreThanOneException
     * @throws Exception
     */
    private function listOfRecommendedFilter(string $value): array
    {
        $movies = Movie::getAll();

        return match ($value) {
            RecommendedFilterMovieEnum::THREE_RANDOM_MOVIES->value => $this->recommendedFilterService->randomSelectElements(array: $movies, number: 3),
            RecommendedFilterMovieEnum::ALL_MOVIES_LETTER_W_AND_EVEN_NUM_CHAR->value =>
                $this->recommendedFilterService->getElementAfterSelectedLetterAndItIsEvenOrOdd(array: $movies, selectLetter: self::SELECT_LETTER, isEven: true),
            RecommendedFilterMovieEnum::TITLE_MORE_THAN_ONE_WORD->value => $this->recommendedFilterService->getElementWithSelectedNumberWords(array: $movies, numberWords: 1),
            default => throw new Exception(message: 'Unexpected match value.')
        };
    }
}
