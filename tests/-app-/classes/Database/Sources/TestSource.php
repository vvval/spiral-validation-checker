<?php

namespace TestApplication\Database\Sources;

use Spiral\ORM\Entities\RecordSource;
use TestApplication\Database\TestRecord;

class TestSource extends RecordSource
{
    const RECORD = TestRecord::class;
}