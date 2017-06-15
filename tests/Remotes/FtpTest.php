<?php

namespace SSDTest\Remotes;


use SSDTest\BaseCase;

use League\Flysystem\FilesystemInterface;

class FtpTest extends BaseCase
{
    /**
     * @test
     */
    public function remote_returns_filesystem_instance()
    {
        $ftp = $this->ftpInstance();
        $this->assertInstanceOf(FilesystemInterface::class, $ftp->remote);
    }

}