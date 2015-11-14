<?php namespace SSD\Backup\Jobs;

use InvalidArgumentException;

use SSD\Backup\Contracts\Directory as DirectoryContract;
use SSD\Backup\Contracts\Filesystem as FilesystemContract;

class Directory extends Filesystem implements DirectoryContract, FilesystemContract
{
    /**
     * Directory constructor.
     * @param string $fullPath
     * @param null|string $rootPath
     */
    public function __construct($fullPath, $rootPath = null)
    {
        if ( ! is_dir($fullPath)) {
            throw new InvalidArgumentException("{$fullPath} is not a valid directory.");
        }

        $this->fullPath = $fullPath;

        if (is_null($rootPath)) {
            return;
        }

        if ( ! is_dir($rootPath)) {
            throw new InvalidArgumentException("{$rootPath} is not a valid directory.");
        }

        $this->rootPath = $rootPath;
    }

}