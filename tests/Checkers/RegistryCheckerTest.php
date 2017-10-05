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
        $this->assertArrayHasKey('field', $validator->getErrors());

        //not null value
        $validator->setData(['field' => false]);
        $this->assertTrue($validator->isValid(), 'Validation FAILED');
    }

    public function testAnyValueAndPassErrorToField()
    {
        $rules = [
            'field' => [
                ['registry::anyValue']
            ],
        ];
        $validator = $this->createValidator($rules);

        $validator->setData(['field' => null]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');
        $this->assertArrayHasKey('field', $validator->getErrors());

        $rules = [
            'field' => [
                ['registry::anyValue', 'test-field']
            ],
        ];
        $validator->setRules($rules);

        $validator->setData(['field' => null]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');
        $this->assertArrayHasKey('test-field', $validator->getErrors());
        $this->assertArrayNotHasKey('field', $validator->getErrors());
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

    public function testAllowedValuesAndPassErrorToField()
    {
        $source = $this->container->get(TestSource::class);
        $this->fill($source, ['a', 'b', 'c', 'd']);

        $rules = [
            'field' => [
                ['registry::allowedValues', TestSource::class, 'field']
            ],
        ];
        $validator = $this->createValidator($rules);

        //not in array
        $validator->setData(['field' => ['aa']]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');
        $this->assertArrayHasKey('field', $validator->getErrors());

        $rules = [
            'field' => [
                ['registry::allowedValues', TestSource::class, 'field', 'test-field']
            ],
        ];
        $validator->setRules($rules);

        //errors will be in main error (global error) and for each sub-value (static error field)
        $validator->setData(['field' => ['aa']]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');
        $this->assertArrayHasKey('test-field', $validator->getErrors());
        $this->assertArrayHasKey('field', $validator->getErrors());

        $rules = [
            'field' => [
                ['registry::allowedValues', TestSource::class, 'field', 'test-field-%s']
            ],
        ];
        $validator->setRules($rules);

        //errors will be in main error (global error) and for each sub-value (dynamic value)
        $validator->setData(['field' => ['aa', 'bb']]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');
        $this->assertArrayHasKey('test-field-aa', $validator->getErrors());
        $this->assertArrayHasKey('test-field-bb', $validator->getErrors());
        $this->assertArrayHasKey('field', $validator->getErrors());
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