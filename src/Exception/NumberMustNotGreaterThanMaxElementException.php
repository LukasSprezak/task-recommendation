<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

/**
 * @codeCoverageIgnore
 */
class NumberMustNotGreaterThanMaxElementException extends Exception
{
    public function __construct(int $number)
    {
        $message = sprintf('The number of films selected may not exceed the maximum number of elements. "%s"', $number);
        parent::__construct($message);
    }
}
