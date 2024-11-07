<?php

namespace SSD\Backup\Jobs;

use InvalidArgumentException;
use SSD\Backup\Contracts\Job as JobContract;

abstract class Filesystem implements JobContract
{
    /**
     * Absolute path with file / directory name.
     */
    protected string $fullPath;

    /**
     * Absolute path to the root directory.
     */
    protected ?string $rootPath;

    /**
     * Filesystem constructor.
     */
    public function __construct(string $fullPath, ?string $rootPath = null)
    {
        $this->setFullPath($fullPath);

        ! is_null($rootPath)
            ? $this->setRootPath($rootPath)
            : $this->rootPath = null;
    }

    /**
     * Extract and return asset path starting from root directory.
     */
    public function asset(): string
    {
        if (is_null($this->rootPath)) {

            $partials = explode('/', $this->fullPath);

            return array_pop($partials);

        }

        return substr($this->fullPath, strlen(rtrim($this->rootPath, '/')) + 1);
    }

    /**
     * Set full path.
     */
    abstract public function setFullPath(string $fullPath): void;

    /**
     * Get full path.
     */
    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    /**
     * Set root path.
     */
    public function setRootPath(string $rootPath): void
    {
        if (! is_dir($rootPath)) {
            throw new InvalidArgumentException(sprintf(
                '%s is not a valid directory.', $rootPath
            ));
        }

        $this->rootPath = $rootPath;
    }

    /**
     * Get root path.
     */
    public function getRootPath(): string
    {
        return $this->rootPath ?? '';
    }
}
