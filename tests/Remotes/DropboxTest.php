<?php

namespace SSDTest\Remotes;

use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\Attributes\Test;
use SSDTest\BaseCase;

class DropboxTest extends BaseCase
{
    #[Test]
    public function remote_returns_filesystem_instance(): void
    {
        $dropbox = $this->dropboxInstance();
        $this->assertInstanceOf(FilesystemOperator::class, $dropbox->remote);
    }
}
