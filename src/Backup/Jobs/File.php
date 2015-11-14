<?php namespace SSD\Backup\Jobs;

use InvalidArgumentException;
use SSD\Backup\Contracts\File as FileContract;
use SSD\Backup\Contracts\Filesystem as FilesystemContract;

class File extends Filesystem implements FileContract, FilesystemContract
{
    /**
     * File constructor.
     * @param string $fullPath
     * @param null|string $rootPath
     */
    public function __construct($fullPath, $rootPath = null)
    {
        if ( ! is_file($fullPath)) {
            throw new InvalidArgumentException("{$fullPath} is not a valid file.");
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