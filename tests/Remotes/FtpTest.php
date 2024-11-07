<?php

namespace SSDTest\Remotes;

use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\Attributes\Test;
use SSDTest\BaseCase;

class FtpTest extends BaseCase
{
    #[Test]
    public function remote_returns_filesystem_instance(): void
    {
        $ftp = $this->ftpInstance();
        $this->assertInstanceOf(FilesystemOperator::class, $ftp->remote);
    }
}
