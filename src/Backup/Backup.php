<?php namespace SSD\Backup;

use ZipArchive;
use InvalidArgumentException;
use Carbon\Carbon;
use League\Flysystem\MountManager;

use SSD\Backup\Contracts\Database;
use SSD\Backup\Contracts\Directory;
use SSD\Backup\Contracts\File;

use SSD\Backup\Jobs\Job;
use SSD\Backup\Remotes\Remote;

use SSD\Backup\Processors\Database as DatabaseProcessor;
use SSD\Backup\Processors\File as FileProcessor;
use SSD\Backup\Processors\Directory as DirectoryProcessor;
use SSD\Backup\Processors\Archive as ArchiveProcessor;
use SSD\Backup\Processors\Distributor as DistributorProcessor;
use SSD\Backup\Processors\Cleanup as CleanupProcessor;


class Backup
{
    /**
     * Remote object instance.
     *
     * @var Remote
     */
    public $remote;

    /**
     * MountManager object instance.
     *
     * @var MountManager
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
     * @var null|string
     */
    private $remoteBackupDir = null;

    /**
     * Name of the archive file.
     *
     * @var string
     */
    private $archiveName = null;

    /**
     * Collection of jobs to be processed.
     *
     * @var array
     */
    private $jobs;

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
     * @var int
     */
    private $noOfBackups = 0;


    /**
     * Backup constructor.
     *
     * @param Remote $remote
     * @param null|string $localWorkingDir
     */
    public function __construct(
        Remote $remote,
        $localWorkingDir = null
    )
    {
        if (is_null($localWorkingDir) || ! is_dir($localWorkingDir)) {
            throw new InvalidArgumentException('Invalid local working directory.');
        }

        $this->remote = $remote;
        $this->localWorkingDir = $localWorkingDir;
    }

    /**
     * Get path to the local working directory.
     *
     * @return null|string
     */
    public function getLocalWorkingDirectory()
    {
        return $this->localWorkingDir;
    }

    /**
     * Set remote directory.
     *
     * @param $directory
     *
     * @return $this
     */
    public function setRemoteDirectory($directory)
    {
        $this->remoteBackupDir = $directory;

        return $this;
    }

    /**
     * Get remote directory name.
     *
     * @return null|string
     */
    public function getRemoteDirectory()
    {
        return $this->remoteBackupDir;
    }

    /**
     * Set archive name.
     *
     * @param $name
     * @return $this
     */
    public function setArchiveName($name)
    {
        $this->archiveName = $name;

        return $this;
    }

    /**
     * Get archive name.
     *
     * @return string
     */
    public function getArchiveName()
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
    public function archivePath()
    {
        return $this->getLocalWorkingDirectory() . DIRECTORY_SEPARATOR . $this->getArchiveName();
    }

    /**
     * Add new job.
     *
     * @param Job $job
     * @return $this
     */
    public function addJob(Job $job)
    {
        $this->jobs[] = $job;

        return $this;
    }

    /**
     * Get all jobs.
     *
     * @return array
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Overwrite default number of backups
     * to be stored on the remote server
     * before being overwritten
     *
     * @param int $number
     */
    public function setNumberOfBackups($number = 7)
    {
        if ( ! is_int($number)) {
            throw new InvalidArgumentException('Number of backups has to be represented by integer.');
        }

        $this->noOfBackups = $number;
    }

    /**
     * Get number of backups.
     *
     * @return int
     */
    public function getNumberOfBackups()
    {
        return $this->noOfBackups;
    }

    /**
     * Execute backup.
     *
     * @return bool
     */
    public function run()
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
     * @return void
     */
    public function prepare()
    {
        if (empty($this->localWorkingDir) || ! is_dir($this->localWorkingDir)) {
            throw new InvalidArgumentException('Invalid local working directory.');
        }

        if (empty($this->jobs)) {
            throw new InvalidArgumentException('There are no jobs available.');
        }

        $this->segregateJobs();
    }

    /**
     * Segregate jobs by its implementation.
     *
     * @return void
     */
    private function segregateJobs()
    {
        foreach($this->jobs as $job) {

            $this->assignJob($job);

        }
    }

    /**
     * Assign job to the right collection.
     *
     * @param Job $job
     */
    private function assignJob(Job $job)
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
     * @param array $item
     * @param string $namespace
     */
    public function addToCollection(array $item, $namespace = '')
    {
        $this->collection[$namespace][] = $item;
    }

    /**
     * Get entire collection.
     *
     * @return array
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Add file to the removal at clean up.
     *
     * @param $path
     */
    public function addToRemoval($path)
    {
        $this->removal[] = $path;
    }

    /**
     * Get removal collection
     *
     * @return array
     */
    public function getRemoval()
    {
        return $this->removal;
    }

    /**
     * Reset collection array.
     *
     * @return void
     */
    public function resetCollection()
    {
        $this->collection = [];
    }

    /**
     * Export all databases.
     *
     * @return void
     */
    public function processDatabases()
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
    public function processFiles()
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
    public function processDirectories()
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
    private function archive()
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
    private function send()
    {
        $distributor = new DistributorProcessor($this);

        $distributor->execute();
    }

    /**
     * Remove all files.
     *
     * @return void
     */
    private function cleanup()
    {
        $cleanup = new CleanupProcessor($this);

        $cleanup->execute();

        if ($this->noOfBackups !== 0) {
            $cleanup->clearOutdated();
        }
    }
}