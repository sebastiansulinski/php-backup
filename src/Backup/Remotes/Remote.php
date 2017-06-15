<?php

namespace SSD\Backup\Remotes;

abstract class Remote
{
    /**
     * Filesystem object.
     *
     * @var \League\Flysystem\FilesystemInterface
     */
    public $remote;
}