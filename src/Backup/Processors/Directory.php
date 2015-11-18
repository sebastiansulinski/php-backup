<?php namespace SSD\Backup\Processors;

use SSD\Backup\Contracts\Processor;
use SSD\Backup\Contracts\Filesystem as FilesystemContract;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\AdapterInterface;

class Directory extends Filesystem implements Processor
{
    /**
     * Add directory and its content to the collection.
     *
     * @param FilesystemContract $directory
     * @param string $namespace
     */
    protected function add(FilesystemContract $directory, $namespace = '')
    {
        $filesystem = new LeagueFilesystem(
            new Local(
                $directory->rootPath(),
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
     * @param FilesystemContract $directory
     * @param string $namespace
     * @param array $collection
     */
    private function addToCollection(FilesystemContract $directory, $namespace = '', $collection)
    {
        foreach ($collection as $item) {

            $fullPath = $directory->rootPath() . DIRECTORY_SEPARATOR . $item['path'];

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
     * @param FilesystemContract $directory
     * @param $path
     * @param $fullPath
     * @return bool
     */
    private function isExcluded(FilesystemContract $directory, $path, $fullPath)
    {
        foreach($directory->exclude as $excluded) {

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