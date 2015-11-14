<?php namespace SSD\Backup\Jobs;

use ReflectionClass;
use InvalidArgumentException;

use Carbon\Carbon;

abstract class Database
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
     * Set database host.
     *
     * @param $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Set database name.
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set database username.
     *
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set database password.
     *
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set database port.
     *
     * @param $port
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Set export file name.
     *
     * @param $name
     * @return $this
     */
    public function setFileName($name)
    {
        $this->file_name = $name;

        return $this;
    }

    /**
     * Get export file name.
     *
     * @return null|string
     */
    public function fileName()
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
    public function isValid()
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