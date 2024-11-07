<?php

namespace SSD\Backup\Processors;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\MountManager;
use SSD\Backup\Backup;

class Distributor
{
    /**
     * Backup object instance.
     *
     * @var \SSD\Backup\Backup
     */
    protected $backup;

    /**
     * Distribution constructor.
     */
    public function __construct(Backup $backup)
    {
        $this->backup = $backup;

        $local = new LeagueFilesystem(
            new Local(
                $this->backup->getLocalWorkingDirectory(),
                LOCK_EX,
                Local::SKIP_LINKS
            ),
            [
                'visibility' => AdapterInterface::VISIBILITY_PUBLIC,
            ]
        );

        $this->backup->manager = new MountManager([
            'local' => $local,
            'remote' => $this->backup->remote->remote,
        ]);
    }

    /**
     * Execute job.
     */
    public function execute(): void
    {
        $remoteDirectory = $this->backup->getRemoteDirectory();
        $archiveFile = $this->backup->getArchiveName();

        if (! $this->backup->manager->has("remote://{$remoteDirectory}")) {

            $this->backup->manager->createDir("remote://{$remoteDirectory}");

        }

        $this->backup->manager->move(
            'local://'.$archiveFile,
            'remote://'.$this->backup->getRemoteDirectory().'/'.$archiveFile
        );
    }
}
