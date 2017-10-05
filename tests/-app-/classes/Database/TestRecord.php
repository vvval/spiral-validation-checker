<?php

namespace TestApplication\Database;

use Spiral\ORM\Record;

class TestRecord extends Record
{
    const SCHEMA = [
        'id'    => 'primary',
        'field' => 'string(32)'
    ];

    const INDEXES = [
        [self::UNIQUE, 'field']
    ];
}