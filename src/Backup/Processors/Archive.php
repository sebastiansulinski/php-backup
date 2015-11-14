<?php namespace SSD\Backup\Processors;

use ZipArchive;

use SSD\Backup\Backup;
use SSD\Backup\Contracts\Processor;

class Archive implements Processor
{
    /**
     * Backup object instance.
     *
     * @var Backup
     */
    private $backup;

    /**
     * ZipArchive object instance.
     *
     * @var ZipArchive
     */
    private $archive;

    /**
     * Archive constructor.
     *
     * @param Backup $backup
     * @param ZipArchive $archive
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
    public function execute()
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
    private function processCollection()
    {
        foreach($this->backup->getCollection() as $namespace => $items) {

            foreach($items as $item) {

                if (is_dir($item['path'])) {

                    $this->archive->addEmptyDir($this->namespacedName($namespace, $item));

                } else {

                    $this->archive->addFile(
                        $item['path'],
                        $this->namespacedName($namespace, $item)
                    );

                }

            }

        }
    }

    /**
     * Prepend namespace to the path where available.
     *
     * @param string $namespace
     * @param $item
     * @return string
     */
    private function namespacedName($namespace = '', $item)
    {
        return ( ! empty($namespace) ) ? $namespace . '/' . $item['name'] : $item['name'];
    }
}