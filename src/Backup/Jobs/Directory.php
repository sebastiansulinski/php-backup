<?php

namespace SSD\Backup\Jobs;

use InvalidArgumentException;
use SSD\Backup\Contracts\Directory as DirectoryContract;

class Directory extends Filesystem implements DirectoryContract
{
    /**
     * Collection of directories
     * excluded from backup.
     *
     * @var string[]
     */
    public array $exclude;

    /**
     * Directory constructor.
     */
    public function __construct(string $fullPath, ?string $rootPath = null, array $exclude = [])
    {
        parent::__construct($fullPath, $rootPath);

        $this->setExclude($exclude);
    }

    /**
     * Set full path.
     */
    public function setFullPath(string $fullPath): void
    {
        if (! is_dir($fullPath)) {
            throw new InvalidArgumentException(sprintf(
                '%s is not a valid directory.', $fullPath
            ));
        }

        $this->fullPath = $fullPath;
    }

    /**
     * Set directories to be excluded.
     */
    private function setExclude(array $exclude = []): void
    {
        $this->exclude = ! empty($exclude)
            ? array_map([$this, 'trimExcludePaths'], $exclude)
            : [];
    }

    /**
     * Trim the directory separator.
     */
    private function trimExcludePaths(string $item): string
    {
        return ltrim($item, DIRECTORY_SEPARATOR);
    }
}
