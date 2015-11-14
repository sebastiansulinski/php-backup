<?php namespace SSD\Backup\Jobs;


class Job
{
    /**
     * Job object instance.
     *
     * @var \SSD\Backup\Contracts\Filesystem|\SSD\Backup\Contracts\Database
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
     * @param \SSD\Backup\Contracts\Database|\SSD\Backup\Contracts\Filesystem $job
     * @param string $namespace
     */
    public function __construct($job, $namespace = '')
    {
        $this->job = $job;
        $this->namespace = $namespace;
    }


}