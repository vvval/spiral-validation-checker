<?php

namespace Vvval\Spiral\Validation\Tests\Checkers;

use Vvval\Spiral\Validation\Tests\BaseTest;

class FieldsCheckerTest extends BaseTest
{
    public function testEqualsTo()
    {
        $rules = [
            'field2' => [
                ['fields::equalsTo', 'field1']
            ],
        ];
        $validator = $this->createValidator($rules);
        $validator->setData(['field' => 'value']);

        //no data
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        //no data
        $validator->setData(['field1' => 'value1']);
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        //no equal data
        $validator->setData(['field2' => 'value2']);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');

        //no equal data
        $validator->setData([
            'field1' => 'value1',
            'field2' => 'value2',
        ]);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');

        //equal data
        $validator->setData([
            'field1' => 'value1',
            'field2' => 'value1',
        ]);
        $this->assertTrue($validator->isValid(), 'Validation FAILED');
    }
}