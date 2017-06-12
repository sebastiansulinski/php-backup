<?php

namespace SSD\Backup\Jobs;


use SSD\Backup\Contracts\Job as JobContract;

use Carbon\Carbon;
use ReflectionClass;
use InvalidArgumentException;

abstract class Database implements JobContract
{
    /**
     * Host name.
     *
     * @var string
     */
    public $host = 'localhost';

    /**
     * Database name.
     *
     * @var string
     */
    public $name;

    /**
     * Dabase user name.
     *
     * @var string
     */
    public $user;

    /**
     * Database password.
     *
     * @var mixed
     */
    public $password;

    /**
     * Database port.
     *
     * @var int
     */
    public $port;

    /**
     * Export file name.
     *
     * @var null
     */
    public $file_name = null;

    /**
     * Database constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (empty($params)) {
            return;
        }

        $reflection = new ReflectionClass($this);

        foreach($params as $key => $value) {

            if ( ! $reflection->hasProperty($key)) {
                throw new InvalidArgumentException("Property {$key} is invalid.");
            }

            $this->{$key} = $value;

        }
    }

    /**
     * Database type.
     *
     * @return string
     */
    abstract public function type() : string;

    /**
     * Set database host.
     *
     * @param $host
     * @return self
     */
    public function setHost($host) : self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Set database name.
     *
     * @param $name
     * @return self
     */
    public function setName($name) : self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set database username.
     *
     * @param $user
     * @return self
     */
    public function setUser($user) : self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set database password.
     *
     * @param $password
     * @return self
     */
    public function setPassword($password) : self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set database port.
     *
     * @param $port
     * @return self
     */
    public function setPort($port) : self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Set export file name.
     *
     * @param $name
     * @return self
     */
    public function setFileName($name) : self
    {
        $this->file_name = $name;

        return $this;
    }

    /**
     * Get export file name.
     *
     * @return string
     */
    public function fileName() : string
    {
        if (is_null($this->file_name)) {
            $this->file_name = $this->name . '_' . Carbon::now()->format('Y-m-d_H-i-s');
        }

        return rtrim($this->file_name, '.sql').'.sql';
    }

    /**
     * Check if all properties have values.
     *
     * @return bool
     */
    public function isValid() : bool
    {
        $params = array_filter(
            [
                $this->host,
                $this->name,
                $this->user,
                $this->password,
                $this->port
            ],
            function($item) {
                return ! empty($item);
            }
        );

        return count($params) === 5;
    }
}