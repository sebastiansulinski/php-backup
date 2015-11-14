<?php namespace SSD\Backup\Jobs;

use SSD\Backup\Contracts\Database as DatabaseContract;

class PostgreSQLDatabase extends Database implements DatabaseContract
{
    /**
     * Database port.
     *
     * @var int
     */
    public $port = 5432;

    /**
     * Database type.
     *
     * @return string
     */
    public function type()
    {
        return 'postgresql';
    }
}