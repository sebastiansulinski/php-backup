<?php namespace SSD\Backup\Remotes;

use League\Flysystem\FilesystemInterface;

abstract class Remote
{
    /**
     * Filesystem object.
     *
     * @var FilesystemInterface
     */
    public $remote;
}