<?php namespace SSD\Backup\Processors;

use SSD\Backup\Backup;

use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\MountManager;

class Distributor
{
    /**
     * Backup object instance.
     *
     * @var Backup
     */
    protected $backup;


    /**
     * Distribution constructor.
     *
     * @param Backup $backup
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
                'visibility' => AdapterInterface::VISIBILITY_PUBLIC
            ]
        );

        $this->backup->manager = new MountManager([
            'local' => $local,
            'remote' => $this->backup->remote->remote
        ]);
    }

    /**
     * Execute job.
     *
     * @return void
     */
    public function execute()
    {
        $remoteDirectory = $this->backup->getRemoteDirectory();
        $archiveFile = $this->backup->getArchiveName();

        if ( ! $this->backup->manager->has("remote://{$remoteDirectory}")) {

            $this->backup->manager->createDir("remote://{$remoteDirectory}");

        }

        $this->backup->manager->move(
            'local://' . $archiveFile,
            'remote://' . $this->backup->getRemoteDirectory() . '/' . $archiveFile
        );
    }
}