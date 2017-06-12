<?php

namespace SSD\Backup\Jobs;


use SSD\Backup\Contracts\Job as JobContract;

class Job
{
    /**
     * Job object instance.
     *
     * @var \SSD\Backup\Contracts\Job $job
     */
    public $job;

    /**
     * Job namespace.
     *
     * @var string
     */
    public $namespace = '';

    /**
     * Job constructor.
     *
     * @param \SSD\Backup\Contracts\Job $job
     * @param string $namespace
     */
    public function __construct(JobContract $job, string $namespace)
    {
        $this->job = $job;
        $this->namespace = $namespace;
    }
}