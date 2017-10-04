<?php

namespace Vvval\Spiral\Validation\Checkers;

use Spiral\ORM\Entities\RecordSource;
use Spiral\Validation\Prototypes\AbstractChecker;
use Vvval\Spiral\Validation\Checkers\Traits\CustomMessagesTrait;

class RegistryChecker extends AbstractChecker
{
    use CustomMessagesTrait;

    /**
     * Default error messages associated with checker method by name.
     * {@inheritdoc}
     */
    const MESSAGES = [
        'anyValue'      => '[[This value is required]]',
        'allowedValues' => '[[This value is not allowed]]'
    ];

    /**
     * Check if any of multiple values is selected (radio buttons or check boxes).
     * This rule will not pass empty condition check, so it has to be added to "emptyConditions" section in the validation config.
     *
     * @param mixed       $value
     * @param string|null $field
     * @param string|null $message
     *
     * @return bool
     */
    public function anyValue($value, string $field = null, string $message = null): bool
    {
        if (!is_null($value)) {
            return true;
        }

        if (!empty($field)) {
            $this->getValidator()->registerError($field, $this->makeMessage($message, __METHOD__));

            return true;
        }

        return false;
    }

    /**
     * Check if array of given values contain only allowed values.
     * Allowed values are taken from database. You can overwrite populate method to get allowed values from another place, for example from config.
     *
     * @param array       $values
     * @param string      $sourceClass
     * @param string      $column
     * @param string|null $field
     * @param string|null $message
     *
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function allowedValues(array $values, string $sourceClass, string $column, string $field = null, string $message = null): bool
    {
        $data = $this->populate($sourceClass, $column);

        $diff = array_diff($values, $data);
        if (!empty($diff) && !empty($field)) {
            foreach ($diff as $item) {
                $this->getValidator()->registerError($this->makeField($field, $item), $this->makeMessage($message, __METHOD__));
            }
        }

        return empty($diff);
    }

    /**
     * Populate registry records and fetch given column to match valid values.
     *
     * @param string $sourceClass
     * @param string $column
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function populate(string $sourceClass, string $column): array
    {
        $source = $this->getSource($sourceClass);
        $input = $source->find()->fetchAll();

        return array_column($input, $column);
    }

    /**
     * Cast record source by its class name.
     *
     * @param string $sourceClass
     *
     * @return \Spiral\ORM\Entities\RecordSource
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getSource(string $sourceClass): RecordSource
    {
        return $this->container->get($sourceClass);
    }

    /**
     * Given error field name can be formatted using sprintf function.
     * Don't forget to add placeholders to render errors
     *
     * Example:
     *      errorField = "registry-error-for-value-%s" (in the request filter)
     *      data-message-placeholder = "registry-error-for-value-test"
     *
     *      If "test" is invalid value, you will receive error in the field "registry-error-for-value-test"
     *
     * @param string $format
     * @param mixed  $value
     *
     * @return string
     */
    private function makeField(string $format, $value): string
    {
        return sprintf($format, $value);
    }
}