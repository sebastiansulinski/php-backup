<?php

namespace SSD\Backup\Jobs;

use SSD\Backup\Contracts\Directory as DirectoryContract;

use InvalidArgumentException;

class Directory extends Filesystem implements DirectoryContract
{
    /**
     * Collection of directories
     * excluded from backup.
     *
     * @var array
     */
    public $exclude = [];

    /**
     * Directory constructor.
     *
     * @param string $fullPath
     * @param string|null $rootPath
     * @param array $exclude
     */
    public function __construct(string $fullPath, string $rootPath = null, array $exclude = [])
    {
        parent::__construct($fullPath, $rootPath);

        if (!empty($exclude)) {
            $this->setExclude($exclude);
        }
    }

    /**
     * Set full path.
     *
     * @param string $fullPath
     */
    public function setFullPath(string $fullPath): void
    {
        if (!is_dir($fullPath)) {
            throw new InvalidArgumentException("{$fullPath} is not a valid directory.");
        }

        $this->fullPath = $fullPath;
    }

    /**
     * Set directories to be excluded.
     *
     * @param  array $exclude
     * @return void
     */
    private function setExclude(array $exclude): void
    {
        $this->exclude = array_map([$this, 'trimExcludePaths'], $exclude);
    }

    /**
     * Trim the directory separator.
     *
     * @param  string $item
     * @return string
     */
    private function trimExcludePaths(string $item): string
    {
        return ltrim($item, DIRECTORY_SEPARATOR);
    }
}