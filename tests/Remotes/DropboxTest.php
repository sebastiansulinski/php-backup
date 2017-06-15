<?php

namespace SSDTest\Remotes;


use SSDTest\BaseCase;

use League\Flysystem\FilesystemInterface;

class DropboxTest extends BaseCase
{
    /**
     * @test
     */
    public function remote_returns_filesystem_instance()
    {
        $dropbox = $this->dropboxInstance();
        $this->assertInstanceOf(FilesystemInterface::class, $dropbox->remote);
    }
}