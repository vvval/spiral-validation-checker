<?php

namespace Vvval\Spiral\Validation\Tests\Checkers;

use Vvval\Spiral\Validation\Tests\BaseTest;
use Vvval\Spiral\Validation\Tests\Checkers\Fixtures\TraitedFixture;

class TraitTest extends BaseTest
{
    public function testTraitMethods()
    {
        $fixture = new TraitedFixture();

        $defaultMessage = $fixture->getMessage('');
        $this->assertNotEmpty($defaultMessage);

        $emptyMessage = $fixture->call(null, __METHOD__);
        $this->assertNotEmpty($emptyMessage);
        $this->assertSame($defaultMessage, $emptyMessage);

        $emptyStringMessage = $fixture->call('', __METHOD__);
        $this->assertNotSame($defaultMessage, $emptyStringMessage);
        $this->assertEquals('', $emptyStringMessage);

        $notEmptyMessage = $fixture->call('test-message', 'method');
        $this->assertNotEmpty($notEmptyMessage);
        $this->assertSame('test-message', $notEmptyMessage);
    }
}