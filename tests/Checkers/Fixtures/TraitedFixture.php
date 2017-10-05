<?php

namespace Vvval\Spiral\Validation\Tests\Checkers\Fixtures;

use Vvval\Spiral\Validation\Checkers\Traits\CustomMessagesTrait;

class TraitedFixture
{
    use CustomMessagesTrait;

    const MESSAGE = 'trait message';

    /**
     * @param string $method
     *
     * @return string
     */
    public function getMessage(string $method): string
    {
        return self::MESSAGE;
    }

    /**
     * @param string|null $message
     * @param string      $method
     *
     * @return string
     */
    public function call(string $message = null, string $method)
    {
        return $this->makeMessage($message, $method);
    }
}