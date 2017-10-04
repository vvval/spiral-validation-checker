<?php

namespace Vvval\Spiral\Validation\Checkers;

use Spiral\Validation\Prototypes\AbstractChecker;

class FieldsChecker extends AbstractChecker
{
    /**
     * Default error messages associated with checker method by name.
     * {@inheritdoc}
     */
    const MESSAGES = [
        'equalsTo' => '[[Values should match.]]',
    ];

    /**
     * Checks equality between given value and another field's value.
     *
     * @param string $value
     * @param string $field
     *
     * @return bool
     */
    public function equalsTo(string $value, string $field): bool
    {
        return $value === $this->getValidator()->getValue($field);
    }
}