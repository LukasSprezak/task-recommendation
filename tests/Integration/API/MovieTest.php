<?php

declare(strict_types=1);

namespace App\Tests\Integration\API;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MovieTest extends WebTestCase
{
    public function testGetAll(): void
    {
        $client = static::createClient();
        $client->request(method: Request::METHOD_GET, uri: '/api/movies');

        $this->assertEquals( expected: Response::HTTP_OK, actual: $client->getResponse()->getStatusCode());
        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider correctFilterCasesInRouteProvider
     */
    public function testRecommendedFilter(): void
    {
        $client = static::createClient();
        $client->request(method: Request::METHOD_GET, uri: '/api/movies/recommended_filter/' . implode(separator: '', array: $this->getProvidedData()));

        $this->assertEquals( expected: Response::HTTP_OK, actual: $client->getResponse()->getStatusCode());
        self::assertResponseIsSuccessful();
    }

    /**
     * @dataProvider unCorrectFilterCasesInRouteProvider
     */
    public function testFailed500RecommendedFilter(): void
    {
        $client = static::createClient();
        $client->request(method: Request::METHOD_GET, uri: '/api/movies/recommended_filter/wrong-parameter' . implode(separator: '', array: $this->getProvidedData()));

        $this->assertEquals( expected: Response::HTTP_INTERNAL_SERVER_ERROR, actual: $client->getResponse()->getStatusCode());
    }

    public function unCorrectFilterCasesInRouteProvider(): Generator
    {
        yield ["three-random-movies123"];
        yield ["all-movies-letter-w-and-even-num-char456"];
        yield ["title-more-than-one-word789"];
    }

    public function correctFilterCasesInRouteProvider(): Generator
    {
        yield ["three-random-movies"];
        yield ["all-movies-letter-w-and-even-num-char"];
        yield ["title-more-than-one-word"];
    }
}
