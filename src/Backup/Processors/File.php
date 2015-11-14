<?php namespace SSD\Backup\Processors;

use SSD\Backup\Contracts\Processor;
use SSD\Backup\Contracts\Filesystem as FilesystemContract;

class File extends Filesystem implements Processor
{
    /**
     * Add single file to the collection.
     *
     * @param FilesystemContract $file
     * @param string $namespace
     */
    protected function add(FilesystemContract $file, $namespace = '')
    {
        $this->backup->addToCollection(
            [
                'name' => $file->asset(),
                'path' => $file->fullPath()
            ],
            $namespace
        );
    }
}