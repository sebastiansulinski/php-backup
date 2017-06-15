<?php

namespace SSD\Backup;

use SSD\Backup\Jobs\Job;
use SSD\Backup\Remotes\Remote;

use SSD\Backup\Jobs\Database;
use SSD\Backup\Contracts\File;
use SSD\Backup\Contracts\Directory;

use SSD\Backup\Processors\File as FileProcessor;
use SSD\Backup\Processors\Archive as ArchiveProcessor;
use SSD\Backup\Processors\Cleanup as CleanupProcessor;
use SSD\Backup\Processors\Database as DatabaseProcessor;
use SSD\Backup\Processors\Directory as DirectoryProcessor;
use SSD\Backup\Processors\Distributor as DistributorProcessor;

use ZipArchive;
use Carbon\Carbon;
use InvalidArgumentException;

class Backup
{
    /**
     * Remote object instance.
     *
     * @var \SSD\Backup\Remotes\Remote
     */
    public $remote;

    /**
     * MountManager object instance.
     *
     * @var \League\Flysystem\MountManager
     */
    public $manager;

    /**
     * Path to the local working directory.
     *
     * @var string
     */
    private $localWorkingDir;

    /**
     * Remote directory to which backup will be saved.
     *
     * @var string
     */
    private $remoteBackupDir;

    /**
     * Name of the archive file.
     *
     * @var string
     */
    private $archiveName;

    /**
     * Collection of jobs to be processed.
     *
     * @var array
     */
    private $jobs = [];

    /**
     * Collection of database jobs.
     *
     * @var array
     */
    private $databases = [];

    /**
     * Collection of directory jobs.
     *
     * @var array
     */
    private $directories = [];

    /**
     * Collection of file jobs.
     *
     * @var array
     */
    private $files = [];

    /**
     * Collection of file / directory names to be added to the archive.
     *
     * @var array
     */
    private $collection = [];

    /**
     * Collection of files to be removed at clean up.
     *
     * @var array
     */
    private $removal = [];

    /**
     * Number of backups before overwritten
     * 0 does not overwrite any backups.
     *
     * @var int
     */
    private $noOfBackups = 0;


    /**
     * Backup constructor.
     *
     * @param \SSD\Backup\Remotes\Remote $remote
     * @param string $localWorkingDir
     */
    public function __construct(
        Remote $remote,
        string $localWorkingDir
    ) {
        if (!is_dir($localWorkingDir)) {
            throw new InvalidArgumentException('Invalid local working directory.');
        }

        $this->remote = $remote;
        $this->localWorkingDir = $localWorkingDir;
    }

    /**
     * Get path to the local working directory.
     *
     * @return string
     */
    public function getLocalWorkingDirectory(): string
    {
        return $this->localWorkingDir;
    }

    /**
     * Set remote directory.
     *
     * @param  string $directory
     * @return self
     */
    public function setRemoteDirectory(string $directory): self
    {
        $this->remoteBackupDir = $directory;

        return $this;
    }

    /**
     * Get remote directory name.
     *
     * @return string
     */
    public function getRemoteDirectory(): string
    {
        return $this->remoteBackupDir;
    }

    /**
     * Set archive name.
     *
     * @param  string $name
     * @return self
     */
    public function setArchiveName(string $name): self
    {
        $this->archiveName = $name;

        return $this;
    }

    /**
     * Get archive name.
     *
     * @return string
     */
    public function getArchiveName(): string
    {
        if (is_null($this->archiveName)) {
            $this->archiveName = Carbon::now()->format('Y-m-d_H-i-s');
        }

        return rtrim($this->archiveName, '.zip').'.zip';
    }

    /**
     * Full path to the archive file.
     *
     * @return string
     */
    public function archivePath(): string
    {
        return $this->getLocalWorkingDirectory().DIRECTORY_SEPARATOR.$this->getArchiveName();
    }

    /**
     * Add new job.
     *
     * @param  \SSD\Backup\Jobs\Job $job
     * @return self
     */
    public function addJob(Job $job): self
    {
        $this->jobs[] = $job;

        return $this;
    }

    /**
     * Get all jobs.
     *
     * @return array
     */
    public function getJobs(): array
    {
        return $this->jobs;
    }

    /**
     * Set number of backups
     * before overwriting.
     *
     * @param  int $number
     * @return void
     */
    public function setNumberOfBackups(int $number): void
    {
        $this->noOfBackups = $number;
    }

    /**
     * Get number of backups.
     *
     * @return int
     */
    public function getNumberOfBackups(): int
    {
        return $this->noOfBackups;
    }

    /**
     * Execute backup.
     *
     * @return void
     */
    public function run(): void
    {
        $this->prepare();

        $this->processDatabases();

        $this->processFiles();

        $this->processDirectories();

        if (empty($this->collection)) {
            return;
        }

        $this->archive();

        $this->send();

        $this->cleanup();
    }

    /**
     * Validate properties and segregate jobs.
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public function prepare(): void
    {
        if (
            is_null($this->localWorkingDir) ||
            !is_dir($this->localWorkingDir)
        ) {
            throw new InvalidArgumentException('Invalid local working directory.');
        }

        if (empty($this->jobs)) {
            throw new InvalidArgumentException('There are no jobs to process.');
        }

        $this->segregateJobs();
    }

    /**
     * Segregate jobs by its implementation.
     *
     * @return void
     */
    private function segregateJobs(): void
    {
        foreach ($this->jobs as $job) {
            $this->assignJob($job);
        }
    }

    /**
     * Assign job to the right collection.
     *
     * @throws InvalidArgumentException
     * @param  Job $job
     * @return void
     */
    private function assignJob(Job $job): void
    {
        if ($job->job instanceof Database) {

            $this->databases[] = $job;

        } elseif ($job->job instanceof Directory) {

            $this->directories[] = $job;

        } elseif ($job->job instanceof File) {

            $this->files[] = $job;

        } else {

            throw new InvalidArgumentException('Job does not implement a valid contract.');

        }
    }

    /**
     * Add item to the collection.
     *
     * @param  array $item
     * @param  string $namespace
     * @return void
     */
    public function addToCollection(array $item, string $namespace): void
    {
        $this->collection[$namespace][] = $item;
    }

    /**
     * Get entire collection.
     *
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * Add file to the removal at clean up.
     *
     * @param  string $path
     * @return void
     */
    public function addToRemoval(string $path): void
    {
        $this->removal[] = $path;
    }

    /**
     * Get removal collection
     *
     * @return array
     */
    public function getRemoval(): array
    {
        return $this->removal;
    }

    /**
     * Reset collection array.
     *
     * @return void
     */
    public function resetCollection(): void
    {
        $this->collection = [];
    }

    /**
     * Export all databases.
     *
     * @return void
     */
    public function processDatabases(): void
    {
        if (empty($this->databases)) {
            return;
        }

        $database = new DatabaseProcessor(
            $this,
            $this->databases,
            $this->localWorkingDir
        );

        $database->execute();
    }

    /**
     * Collect all single files.
     *
     * @return void
     */
    public function processFiles(): void
    {
        if (empty($this->files)) {
            return;
        }

        $file = new FileProcessor(
            $this,
            $this->files
        );

        $file->execute();
    }

    /**
     * Collect all directories recursively.
     *
     * @return void
     */
    public function processDirectories(): void
    {
        if (empty($this->directories)) {
            return;
        }

        $directory = new DirectoryProcessor(
            $this,
            $this->directories
        );

        $directory->execute();
    }

    /**
     * Archive collection.
     *
     * @return void
     */
    private function archive(): void
    {
        $archive = new ArchiveProcessor(
            $this,
            new ZipArchive
        );

        $archive->execute();
    }

    /**
     * Send archive to the repository.
     *
     * @return void
     */
    private function send(): void
    {
        $distributor = new DistributorProcessor($this);

        $distributor->execute();
    }

    /**
     * Remove all files.
     *
     * @return void
     */
    private function cleanup(): void
    {
        $cleanup = new CleanupProcessor($this);

        $cleanup->execute();

        if ($this->noOfBackups !== 0) {
            $cleanup->clearOutdated();
        }
    }
}