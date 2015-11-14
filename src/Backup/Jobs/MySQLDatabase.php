<?php namespace SSD\Backup\Jobs;

use SSD\Backup\Contracts\Database as DatabaseContract;

class MySQLDatabase extends Database implements DatabaseContract
{
    /**
     * Database port.
     *
     * @var int
     */
    public $port = 3306;

    /**
     * Database type.
     *
     * @return string
     */
    public function type()
    {
        return 'mysql';
    }
}