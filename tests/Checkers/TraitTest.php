<?php

namespace Vvval\Spiral\Validation\Tests\Checkers;

use Vvval\Spiral\Validation\Tests\BaseTest;
use Vvval\Spiral\Validation\Tests\Checkers\Fixtures\TraitedFixture;

class TraitTest extends BaseTest
{
    /**
     * @dataProvider provider
     *
     * @param string $method
     */
    public function testTraitMethods(string $method)
    {
        $fixture = new TraitedFixture();

        $defaultMessage = $fixture->getMessage('');
        $this->assertNotEmpty($defaultMessage);

        //no custom message
        $missingMessage = $fixture->call(null, $method);
        $this->assertNotEmpty($missingMessage);
        $this->assertSame($defaultMessage, $missingMessage);

        //custom message is empty string (is set, so it exists)
        $emptyStringMessage = $fixture->call('', $method);
        $this->assertNotSame($defaultMessage, $emptyStringMessage);
        $this->assertEquals('', $emptyStringMessage);

        $notEmptyMessage = $fixture->call('test-message', $method);
        $this->assertNotEmpty($notEmptyMessage);
        $this->assertSame('test-message', $notEmptyMessage);
    }

    /**
     * @return array
     */
    public function provider()
    {
        return [
            [__METHOD__],
            ['test-method']
        ];
    }
}