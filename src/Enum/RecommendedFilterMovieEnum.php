<?php

declare(strict_types=1);

namespace App\Enum;

enum RecommendedFilterMovieEnum: string
{
    case THREE_RANDOM_MOVIES = 'three-random-movies';
    case ALL_MOVIES_LETTER_W_AND_EVEN_NUM_CHAR = 'all-movies-letter-w-and-even-num-char';
    case TITLE_MORE_THAN_ONE_WORD = 'title-more-than-one-word';
}
