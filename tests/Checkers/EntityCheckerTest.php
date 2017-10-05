<?php

namespace Vvval\Spiral\Validation\Tests\Checkers;

use TestApplication\Database\Sources\TestSource;
use TestApplication\Database\TestRecord;
use Vvval\Spiral\Validation\Checkers\EntityChecker;
use Vvval\Spiral\Validation\Tests\BaseTest;

class EntityCheckerTest extends BaseTest
{
    public function testIsUnique()
    {
        $rules = [
            'field' => [
                [EntityChecker::class . '::isUnique', TestSource::class, 'field']
            ],
        ];
        $validator = $this->createValidator($rules);
        $validator->setData(['field' => 'value']);

        //nothing in db
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        /**
         * @var TestSource $source
         * @var TestRecord $record
         */
        $source = $this->container->get(TestSource::class);
        $entity = $source->create();

        //nothing in db (entity not saved)
        $validator->setContext($entity);
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        //nothing in db (entity not saved)
        $entity->field = 'value';
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        //entity in db, but it is passed as context, no conflicts with another entities
        $entity->save();
        $this->assertTrue($validator->isValid(), 'Validation FAILED');

        //entity in db and it isn't passed as context, fail with conflict
        $validator->setContext(null);
        $this->assertFalse($validator->isValid(), 'Validation PASSED');
    }
}