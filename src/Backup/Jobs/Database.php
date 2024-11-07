<?php

namespace SSD\Backup\Jobs;

use Carbon\Carbon;
use InvalidArgumentException;
use ReflectionClass;
use SSD\Backup\Contracts\Job as JobContract;

abstract class Database implements JobContract
{
    /**
     * Host name.
     *
     * @var string
     */
    public $host = '127.0.0.1';

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
     * @var string
     */
    public $password;

    /**
     * Database port.
     *
     * @var int
     */
    public $port = 3306;

    /**
     * Export file name.
     *
     * @var string
     */
    public $fileName;

    /**
     * Database constructor.
     */
    public function __construct(array $props = [])
    {
        if (empty($props)) {
            return;
        }

        $this->setProperties($props);
    }

    /**
     * Set properties.
     */
    public function setProperties(array $props): void
    {
        $reflection = new ReflectionClass($this);

        foreach ($props as $key => $value) {

            if (! $reflection->hasProperty($key)) {
                throw new InvalidArgumentException("Property {$key} is invalid.");
            }

            $this->{$key} = $value;
        }
    }

    /**
     * Database type.
     */
    abstract public function type(): string;

    /**
     * Set database host.
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Set database name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set database username.
     */
    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set database password.
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set database port.
     */
    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Set export file name.
     */
    public function setFileName(string $name): self
    {
        $this->fileName = $name;

        return $this;
    }

    /**
     * Get export file name.
     */
    public function fileName(): string
    {
        if (is_null($this->fileName)) {
            $this->fileName = $this->name.'_'.Carbon::now()->format('Y-m-d_H-i-s');
        }

        return rtrim($this->fileName, '.sql').'.sql';
    }

    /**
     * Check if all properties have values.
     */
    public function isValid(): bool
    {
        return count($this->filterProperties()) === 5;
    }

    /**
     * Filter non empty properties.
     */
    private function filterProperties(): array
    {
        return array_filter(
            [
                $this->host,
                $this->name,
                $this->user,
                $this->password,
                $this->port,
            ],
            function ($item) {
                return ! empty($item);
            }
        );
    }
}
