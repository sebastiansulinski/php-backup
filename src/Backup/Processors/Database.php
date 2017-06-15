<?php

namespace SSD\Backup\Processors;

use SSD\Backup\Backup;
use SSD\Backup\Contracts\Processor;
use SSD\Backup\Jobs\Database as BaseDatabase;

use BackupManager\Manager;
use BackupManager\Databases;
use BackupManager\Filesystems;
use BackupManager\Compressors;
use BackupManager\Config\Config;
use BackupManager\Filesystems\Destination;

class Database implements Processor
{
    /**
     * Instance of the Backup object.
     *
     * @var \SSD\Backup\Backup
     */
    private $backup;

    /**
     * Collection of the database jobs.
     *
     * @var array
     */
    private $jobs;

    /**
     * Path to the working directory.
     *
     * @var string
     */
    private $workingDir;


    /**
     * Database constructor.
     *
     * @param \SSD\Backup\Backup $backup
     * @param array $jobs
     * @param string $workingDir
     */
    public function __construct(Backup $backup, array $jobs, string $workingDir)
    {
        $this->backup = $backup;
        $this->jobs = $jobs;
        $this->workingDir = $workingDir;
    }

    /**
     * Execute backup.
     *
     * @return void
     */
    public function execute(): void
    {
        foreach ($this->jobs as $job) {
            $this->instance($job->job, $job->namespace);
        }
    }

    /**
     * Process single backup job.
     *
     * @param  \SSD\Backup\Jobs\Database $database
     * @param  string $namespace
     * @return void
     */
    private function instance(BaseDatabase $database, string $namespace): void
    {
        $file = $this->workingDir.'/'.$database->fileName();

        if (is_file($file)) {
            unlink($file);
        }

        $filesystems = new Filesystems\FilesystemProvider($this->fileSystemConfig());
        $filesystems->add(new Filesystems\LocalFilesystem);

        $databases = new Databases\DatabaseProvider($this->databaseConfig($database));
        $databases->add(new Databases\MysqlDatabase);
        $databases->add(new Databases\PostgresqlDatabase);

        $compressors = new Compressors\CompressorProvider;
        $compressors->add(new Compressors\NullCompressor);


        $manager = new Manager($filesystems, $databases, $compressors);

        $manager->makeBackup()->run(
            'config',
            [
                new Destination('local', $database->fileName())
            ],
            'null'
        );

        $this->backup->addToCollection(
            [
                'name' => $database->fileName(),
                'path' => $file
            ],
            $namespace
        );

        $this->backup->addToRemoval($file);
    }

    /**
     * Filesystem config.
     *
     * @return \BackupManager\Config\Config
     */
    private function fileSystemConfig(): Config
    {
        return new Config([
            'local' => [
                'type' => 'Local',
                'root' => $this->workingDir,
            ]
        ]);
    }

    /**
     * Configuration for a given database type.
     *
     * @param  \SSD\Backup\Jobs\Database $database
     * @return \BackupManager\Config\Config
     */
    private function databaseConfig(BaseDatabase $database): Config
    {
        return new Config([
            'config' => [
                'type' => $database->type(),
                'host' => $database->host,
                'port' => $database->port,
                'user' => $database->user,
                'pass' => $database->password,
                'database' => $database->name
            ]
        ]);
    }
}