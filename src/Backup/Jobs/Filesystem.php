<?php

namespace SSD\Backup\Jobs;

use SSD\Backup\Contracts\Job as JobContract;

use InvalidArgumentException;

abstract class Filesystem implements JobContract
{
    /**
     * Absolute path with file / directory name.
     *
     * @var string
     */
    protected string $fullPath;

    /**
     * Absolute path to the root directory.
     *
     * @var string|null
     */
    protected ?string $rootPath;

    /**
     * Filesystem constructor.
     *
     * @param string $fullPath
     * @param string|null $rootPath
     */
    public function __construct(string $fullPath, string $rootPath = null)
    {
        $this->setFullPath($fullPath);

        !is_null($rootPath)
            ? $this->setRootPath($rootPath)
            : $this->rootPath = null;
    }

    /**
     * Extract and return asset path starting from root directory.
     *
     * @return string
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
     *
     * @param string $fullPath
     */
    abstract public function setFullPath(string $fullPath): void;

    /**
     * Get full path.
     *
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    /**
     * Set root path.
     *
     * @param string $rootPath
     */
    public function setRootPath(string $rootPath): void
    {
        if (!is_dir($rootPath)) {
            throw new InvalidArgumentException(sprintf(
                '%s is not a valid directory.', $rootPath
            ));
        }

        $this->rootPath = $rootPath;
    }

    /**
     * Get root path.
     *
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath ?? '';
    }
}