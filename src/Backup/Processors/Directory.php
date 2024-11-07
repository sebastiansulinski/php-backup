<?php

namespace SSD\Backup\Processors;

use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Visibility;
use SSD\Backup\Contracts\Processor;
use SSD\Backup\Jobs\Filesystem as FilesystemJob;

class Directory extends Filesystem implements Processor
{
    /**
     * Add directory and its content to the collection.
     *
     * @throws \League\Flysystem\FilesystemException
     */
    protected function add(FilesystemJob $resource, $namespace = ''): void
    {
        $filesystem = new LeagueFilesystem(
            adapter: new LocalFilesystemAdapter(
                location: $resource->getRootPath(),
                writeFlags: LOCK_EX,
                linkHandling: LocalFilesystemAdapter::SKIP_LINKS
            ),
            config: [
                'visibility' => Visibility::PUBLIC,
            ]
        );

        $collection = $filesystem->listContents($resource->asset(), true);

        $this->addToCollection($resource, $collection, $namespace);
    }

    /**
     * Add item to collection.
     */
    private function addToCollection(FilesystemJob $directory, DirectoryListing $collection, string $namespace = ''): void
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
                    'path' => $fullPath,
                ],
                $namespace
            );
        }
    }

    /**
     * Check if a given path is excluded.
     */
    private function isExcluded(FilesystemJob $directory, string $path, string $fullPath): bool
    {
        foreach ($directory->exclude as $excluded) {

            if (str_starts_with($fullPath, $excluded) || str_starts_with($path, $excluded)) {
                return true;
            }
        }

        return false;
    }
}
