<?php namespace SSD\Backup\Processors;

use SSD\Backup\Backup;
use SSD\Backup\Contracts\Processor;

class Cleanup implements Processor
{
    /**
     * Backup object instance.
     *
     * @var Backup
     */
    private $backup;

    /**
     * Cleanup constructor.
     * @param Backup $backup
     */
    public function __construct(Backup $backup)
    {
        $this->backup = $backup;
    }

    /**
     * Execute cleanup.
     *
     * @return $this
     */
    public function execute()
    {
        foreach($this->backup->getRemoval() as $file) {

            if ( ! is_file($file)) {
                continue;
            }

            unlink($file);

        }

        $this->backup->resetCollection();

        return $this;
    }

    /**
     * Remove old backup files.
     *
     * @return void
     */
    public function clearOutdated()
    {
        $files = $this->backup->manager->listContents(
            'remote://' . $this->backup->getRemoteDirectory(),
            true
        );

        $count = count($files);

        if (empty($files) || $count <= $this->backup->getNumberOfBackups()) {
            return;
        }

        $remove = ( $count - $this->backup->getNumberOfBackups() );

        asort($files);

        foreach($files as $key => $file) {

            if ( ($key + 1) > $remove ) {

                return;

            }

            $this->backup->manager->delete(
                'remote://' . $this->backup->getRemoteDirectory() . '/' . $file['basename']
            );

        }
    }
}