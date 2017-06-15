<?php

namespace SSD\Backup\Processors;

use SSD\Backup\Contracts\Processor;
use SSD\Backup\Jobs\Filesystem as FilesystemJob;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem as LeagueFilesystem;

class Directory extends Filesystem implements Processor
{
    /**
     * Add directory and its content to the collection.
     *
     * @param  \SSD\Backup\Jobs\Filesystem $directory
     * @param  string $namespace
     * @return void
     */
    protected function add(FilesystemJob $directory, $namespace = ''): void
    {
        $filesystem = new LeagueFilesystem(
            new Local(
                $directory->getRootPath(),
                LOCK_EX,
                Local::SKIP_LINKS
            ),
            [
                'visibility' => AdapterInterface::VISIBILITY_PUBLIC
            ]
        );

        $collection = $filesystem->listContents($directory->asset(), true);

        $this->addToCollection($directory, $namespace, $collection);
    }

    /**
     * Add item to collection.
     *
     * @param  \SSD\Backup\Jobs\Filesystem $directory
     * @param  string $namespace
     * @param  array $collection
     * @return void
     */
    private function addToCollection(FilesystemJob $directory, $namespace = '', $collection): void
    {
        foreach ($collection as $item) {

            $fullPath = $directory->getRootPath().DIRECTORY_SEPARATOR.$item['path'];

            $trimmedPath = ltrim($item['path'], DIRECTORY_SEPARATOR);
            $trimmedFullPath = ltrim($fullPath, DIRECTORY_SEPARATOR);

            if ($this->isExcluded($directory, $trimmedPath, $trimmedFullPath)) {
                continue;
            }

            $this->backup->addToCollection(
                [
                    'name' => $item['path'],
                    'path' => $fullPath
                ],
                $namespace
            );
        }
    }

    /**
     * Check if a given path is excluded.
     *
     * @param  \SSD\Backup\Jobs\Filesystem $directory
     * @param  string $path
     * @param  string $fullPath
     * @return bool
     */
    private function isExcluded(FilesystemJob $directory, $path, $fullPath): bool
    {
        foreach ($directory->exclude as $excluded) {

            if (
                strpos($fullPath, $excluded) === 0 ||
                strpos($path, $excluded) === 0
            ) {
                return true;
            }
        }

        return false;
    }
}