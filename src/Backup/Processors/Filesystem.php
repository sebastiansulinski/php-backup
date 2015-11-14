<?php namespace SSD\Backup\Processors;

use SSD\Backup\Backup;
use SSD\Backup\Contracts\Filesystem as FilesystemContract;

abstract class Filesystem
{
    /**
     * Backup object instance.
     *
     * @var Backup
     */
    protected $backup;

    /**
     * Collection of jobs.
     *
     * @var array
     */
    protected $jobs;

    /**
     * Constructor.
     * @param Backup $backup
     * @param array $jobs
     */
    public function __construct(Backup $backup, array $jobs)
    {
        $this->backup = $backup;
        $this->jobs = $jobs;
    }

    /**
     * Collect items.
     *
     * @return void
     */
    public function execute()
    {
        foreach($this->jobs as $job) {

            $this->add($job->job, $job->namespace);

        }
    }

    /**
     * Add to the collection.
     *
     * @param FilesystemContract $directory
     * @param string $namespace
     */
    abstract protected function add(FilesystemContract $directory, $namespace = '');

}