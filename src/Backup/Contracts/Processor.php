<?php namespace SSD\Backup\Contracts;


interface Processor
{
    /**
     * Execute job.
     *
     * @return void
     */
    public function execute();
}