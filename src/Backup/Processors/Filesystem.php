<?php

namespace SSD\Backup\Processors;

use SSD\Backup\Backup;
use SSD\Backup\Jobs\Filesystem as FilesystemJob;

abstract class Filesystem
{
    /**
     * Backup object instance.
     *
     * @var \SSD\Backup\Backup
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
     *
     * @param \SSD\Backup\Backup $backup
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
    public function execute(): void
    {
        foreach ($this->jobs as $job) {
            $this->add($job->job, $job->namespace);
        }
    }

    /**
     * Add to the collection.
     *
     * @param  \SSD\Backup\Jobs\Filesystem $resource
     * @param  string $namespace
     * @return void
     */
    abstract protected function add(FilesystemJob $resource, $namespace = ''): void;
}