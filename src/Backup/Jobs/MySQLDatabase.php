<?php

namespace SSD\Backup\Jobs;

class MySQLDatabase extends Database
{
    /**
     * Database port.
     *
     * @var int
     */
    public $port = 3306;

    /**
     * Database type.
     */
    public function type(): string
    {
        return 'mysql';
    }
}
