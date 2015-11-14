<?php namespace SSD\Backup\Jobs;


abstract class Filesystem
{
    /**
     * Absolute path with file / directory name.
     *
     * @var string
     */
    protected $fullPath;

    /**
     * Absolute path to the root directory.
     *
     * @var null|string
     */
    protected $rootPath;

    /**
     * Extract and return asset path starting from root directory.
     *
     * @return mixed
     */
    public function asset()
    {
        if (is_null($this->rootPath)) {

            $partials = explode('/', $this->fullPath);

            return array_pop($partials);

        }

        return substr($this->fullPath, strlen(rtrim($this->rootPath, '/')) + 1);
    }

    /**
     * Get full path.
     *
     * @return mixed
     */
    public function fullPath()
    {
        return $this->fullPath;
    }

    /**
     * Get root path.
     *
     * @return mixed
     */
    public function rootPath()
    {
        return $this->rootPath;
    }

}