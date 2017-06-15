<?php

namespace SSD\Backup\Jobs;

use SSD\Backup\Contracts\File as FileContract;

use InvalidArgumentException;

class File extends Filesystem implements FileContract
{
    /**
     * Set full path.
     *
     * @param string $fullPath
     */
    public function setFullPath(string $fullPath) : void
    {
        if (!is_file($fullPath)) {
            throw new InvalidArgumentException("{$fullPath} is not a valid file.");
        }

        $this->fullPath = $fullPath;
    }
}