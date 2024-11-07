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
     */
    public function __construct(Backup $backup, array $jobs)
    {
        $this->backup = $backup;
        $this->jobs = $jobs;
    }

    /**
     * Collect items.
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
     * @param  string  $namespace
     */
    abstract protected function add(FilesystemJob $resource, $namespace = ''): void;
}
