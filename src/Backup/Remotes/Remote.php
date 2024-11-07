<?php

namespace SSD\Backup\Remotes;

use League\Flysystem\FilesystemOperator;

abstract class Remote
{
    public FilesystemOperator $remote;
}
