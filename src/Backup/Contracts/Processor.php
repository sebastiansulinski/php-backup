<?php

namespace SSD\Backup\Contracts;

interface Processor
{
    /**
     * Execute job.
     */
    public function execute(): void;
}
