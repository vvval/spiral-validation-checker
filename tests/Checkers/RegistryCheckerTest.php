<?php

namespace Vvval\Spiral\Validation\Tests\Checkers;

use TestApplication\Database\Sources\TestSource;
use TestApplication\Database\TestRecord;
use Vvval\Spiral\Validation\Tests\BaseTest;

class RegistryCheckerTest extends BaseTest
{
    /**
     * registry::anyValue has to be in emptyConditions section in the validation config.
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testAnyValue()
    {
        $rules = [
            'field' => [
                'registry::anyValue'
            ],
        ];
        $validator = $this->createValidator($rules);

        //nothing passed or null passed
        $validator->setData([]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');

        $validator->setData(['field' => null]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');

        //not null value
        $validator->setData(['field' => false]);
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        return;
    }

    public function testAllowedValues()
    {
        $source = $this->container->get(TestSource::class);
        $this->fill($source, ['a', 'b', 'c', 'd']);

        $rules = [
            'field' => [
                ['registry::allowedValues', TestSource::class, 'field']
            ],
        ];
        $validator = $this->createValidator($rules);

        //nothing is allowed
        $validator->setData([]);
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        //in array
        $validator->setData(['field' => ['a']]);
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        //not in array
        $validator->setData(['field' => ['aa']]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');

        //mixed
        $validator->setData(['field' => ['a', 'aa']]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');
    }

    /**
     * @param TestSource $source
     * @param array      $input
     */
    protected function fill(TestSource $source, array $input)
    {
        foreach (array_unique($input) as $value) {
            /** @var TestRecord $record */
            $record = $source->create();
            $record->field = $value;
            $record->save();
        }
    }
}