<?php

namespace Vvval\Spiral\Validation\Checkers;

use Spiral\ORM\Entities\RecordSource;
use Spiral\ORM\RecordInterface;
use Spiral\Validation\Prototypes\AbstractChecker;

class EntityChecker extends AbstractChecker
{
    /**
     * Default error messages associated with checker method by name.
     * {@inheritdoc}
     */
    const MESSAGES = [
        'isUnique' => '[[Must be unique value.]]',
    ];

    /**
     * @param mixed  $value
     * @param string $sourceClass
     * @param string $field
     *
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Spiral\Models\Exceptions\EntityExceptionInterface
     */
    public function isUnique($value, string $sourceClass, string $field): bool
    {
        $entity = $this->getEntity();

        //Entity is passed and its value hasn't changed.
        if (!empty($entity) && $entity->getField($field) === $value) {
            return true;
        }

        $source = $this->getSource($sourceClass);

        //Another entity in database with same field value will cause error.
        return !$this->hasAnotherEntity($source, $field, $value);
    }

    /**
     * Fetch entity from validation context.
     *
     * @return RecordInterface|null
     */
    private function getEntity(): ?RecordInterface
    {
        return $this->getValidator()->getContext();
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
    private function getSource(string $sourceClass): RecordSource
    {
        return $this->container->get($sourceClass);
    }

    /**
     * @param RecordSource $source
     * @param string       $field
     * @param mixed        $value
     *
     * @return bool
     */
    private function hasAnotherEntity(RecordSource $source, string $field, $value): bool
    {
        return (bool)$source->findOne([$field => $value]);
    }
}