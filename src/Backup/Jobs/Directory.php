<?php namespace SSD\Backup\Jobs;

use InvalidArgumentException;

use SSD\Backup\Contracts\Directory as DirectoryContract;
use SSD\Backup\Contracts\Filesystem as FilesystemContract;

class Directory extends Filesystem implements DirectoryContract, FilesystemContract
{
    /**
     * @var array
     */
    public $exclude = [];

    /**
     * Directory constructor.
     *
     * @param string $fullPath
     * @param null|string $rootPath
     * @param array $exclude
     */
    public function __construct($fullPath, $rootPath = null, array $exclude = [])
    {
        if ( ! is_dir($fullPath)) {
            throw new InvalidArgumentException("{$fullPath} is not a valid directory.");
        }

        $this->fullPath = $fullPath;

        $this->processExclude($exclude);

        if (is_null($rootPath)) {
            return;
        }

        if ( ! is_dir($rootPath)) {
            throw new InvalidArgumentException("{$rootPath} is not a valid directory.");
        }

        $this->rootPath = $rootPath;
    }

    /**
     * Remove directory separator from the beginning of each path.
     *
     * @param array $exclude
     */
    private function processExclude(array $exclude = [])
    {
        if (empty($exclude)) {
            return;
        }

        $this->exclude = array_map([$this, 'trim'], $exclude);
    }

    /**
     * Trim the directory separator.
     *
     * @param $item
     * @return string
     */
    private function trim($item)
    {
        return ltrim($item, DIRECTORY_SEPARATOR);
    }

}