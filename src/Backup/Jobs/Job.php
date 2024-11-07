<?php

namespace SSD\Backup\Jobs;

use SSD\Backup\Contracts\Job as JobContract;

class Job
{
    /**
     * Job object instance.
     *
     * @var \SSD\Backup\Contracts\Job
     */
    public $job;

    /**
     * Job namespace.
     *
     * @var string
     */
    public $namespace;

    /**
     * Job constructor.
     */
    public function __construct(JobContract $job, string $namespace = '')
    {
        $this->job = $job;
        $this->namespace = $namespace;
    }
}
