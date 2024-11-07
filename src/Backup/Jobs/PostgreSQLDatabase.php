<?php

namespace SSD\Backup\Jobs;

class PostgreSQLDatabase extends Database
{
    /**
     * Database port.
     *
     * @var int
     */
    public $port = 5432;

    /**
     * Database type.
     */
    public function type(): string
    {
        return 'postgresql';
    }
}
