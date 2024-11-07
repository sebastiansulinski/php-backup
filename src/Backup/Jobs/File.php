<?php

namespace SSD\Backup\Jobs;

use InvalidArgumentException;
use SSD\Backup\Contracts\File as FileContract;

class File extends Filesystem implements FileContract
{
    /**
     * Set full path.
     */
    public function setFullPath(string $fullPath): void
    {
        if (! is_file($fullPath)) {
            throw new InvalidArgumentException("{$fullPath} is not a valid file.");
        }

        $this->fullPath = $fullPath;
    }
}
