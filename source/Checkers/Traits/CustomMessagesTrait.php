<?php

namespace Vvval\Spiral\Validation\Checkers\Traits;

trait CustomMessagesTrait
{
    /**
     * Return custom error message or a default one.
     *
     * @param string|null $message
     * @param string      $method
     *
     * @return string
     */
    protected function makeMessage(string $message = null, string $method): string
    {
        return $message ?? $this->getMessage($this->getMethod($method));
    }

    /**
     * Fetch method name.
     *
     * @param string $method
     *
     * @return string
     */
    protected function getMethod(string $method): string
    {
        if (mb_stripos($method, '::') === false) {
            return $method;
        }

        $arr = explode('::', $method);

        return $arr[1];
    }

    /**
     * Return error message for checker.
     *
     * @param string $method
     *
     * @see \Spiral\Validation\CheckerInterface::getMessage()
     *
     * @return string
     */
    abstract public function getMessage(string $method): string;
}