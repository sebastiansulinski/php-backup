<?php

namespace SSD\Backup\Processors;

use SSD\Backup\Backup;
use SSD\Backup\Contracts\Processor;

use ZipArchive;

class Archive implements Processor
{
    /**
     * Backup object instance.
     *
     * @var \SSD\Backup\Backup
     */
    private $backup;

    /**
     * ZipArchive object instance.
     *
     * @var \ZipArchive
     */
    private $archive;

    /**
     * Archive constructor.
     *
     * @param \SSD\Backup\Backup $backup
     * @param \ZipArchive $archive
     */
    public function __construct(Backup $backup, ZipArchive $archive)
    {
        $this->backup = $backup;
        $this->archive = $archive;
    }

    /**
     * Convert collection to the archive.
     *
     * @return void
     */
    public function execute(): void
    {
        if (
            $this->archive->open(
                $this->backup->archivePath(),
                ZipArchive::CREATE | ZipArchive::OVERWRITE
            ) === true
        ) {

            $this->processCollection();

        }

        $this->archive->close();

        $this->backup->addToRemoval($this->backup->archivePath());
    }

    /**
     * Add files from the collection to the archive.
     *
     * @return void
     */
    private function processCollection(): void
    {
        foreach ($this->backup->getCollection() as $namespace => $items) {
            $this->processItemGroup($items, $namespace);
        }
    }

    /**
     * Process item group.
     *
     * @param  array $items
     * @param  string $namespace
     * @return void
     */
    private function processItemGroup(array $items, string $namespace): void
    {
        foreach ($items as $item) {

            if (is_dir($item['path'])) {

                $this->archive->addEmptyDir($this->namespacedName($item, $namespace));

            } else {

                $this->archive->addFile(
                    $item['path'],
                    $this->namespacedName($item, $namespace)
                );

            }
        }
    }

    /**
     * Prepend namespace to the path.
     *
     * @param  string $namespace
     * @param  array $item
     * @return string
     */
    private function namespacedName(array $item, string $namespace): string
    {
        return $namespace.'/'.$item['name'];
    }
}