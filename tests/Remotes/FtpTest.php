<?php namespace SSDTest\Remotes;

use PHPUnit_Framework_Error;

use SSD\Backup\Remotes\Ftp;
use SSDTest\BaseCase;

use League\Flysystem\FilesystemInterface;

class FtpTest extends BaseCase
{
    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function constructor_throws_error_without_arguments()
    {
        $ftp = new Ftp();
    }

    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function constructor_throws_error_with_only_one_argument()
    {
        $ftp = new Ftp('foo');
    }

    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function constructor_throws_error_with_only_two_arguments()
    {
        $ftp = new Ftp('foo', 'bar');
    }

    /**
     * @test
     */
    public function remote_returns_filesystem_instance()
    {
        $ftp = $this->ftpInstance();
        $this->assertInstanceOf(FilesystemInterface::class, $ftp->remote);
    }

}