<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

/**
 * @codeCoverageIgnore
 */
class NumberMoreThanOneException extends Exception
{
    public function __construct(int $number)
    {
        $message = sprintf('The list of films must not be less than one. "%s"', $number);
        parent::__construct($message);
    }
}
