<?php

namespace SSD\Backup\Jobs;


use SSD\Backup\Contracts\Job as JobContract;

abstract class Filesystem implements JobContract
{
    /**
     * Absolute path with file / directory name.
     *
     * @var string
     */
    protected $fullPath = '';

    /**
     * Absolute path to the root directory.
     *
     * @var string
     */
    protected $rootPath = '';

    /**
     * Extract and return asset path starting from root directory.
     *
     * @return string
     */
    public function asset() : string
    {
        if ($this->rootPath === '') {

            $partials = explode('/', $this->fullPath);

            return array_pop($partials);

        }

        return substr($this->fullPath, strlen(rtrim($this->rootPath, '/')) + 1);
    }

    /**
     * Get full path.
     *
     * @return string
     */
    public function fullPath() : string
    {
        return $this->fullPath;
    }

    /**
     * Get root path.
     *
     * @return string
     */
    public function rootPath() : string
    {
        return $this->rootPath;
    }
}