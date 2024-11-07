<?php

namespace SSD\Backup\Processors;

use SSD\Backup\Contracts\Processor;
use SSD\Backup\Jobs\Filesystem as FilesystemJob;

class File extends Filesystem implements Processor
{
    /**
     * Add single file to the collection.
     *
     * @param  string  $namespace
     */
    protected function add(FilesystemJob $file, $namespace = ''): void
    {
        $this->backup->addToCollection(
            [
                'name' => $file->asset(),
                'path' => $file->getFullPath(),
            ],
            $namespace
        );
    }
}
