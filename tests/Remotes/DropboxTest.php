<?php namespace SSDTest\Remotes;

use PHPUnit_Framework_Error;

use SSD\Backup\Remotes\Dropbox;
use SSDTest\BaseCase;

use League\Flysystem\FilesystemInterface;

class DropboxTest extends BaseCase
{
    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function constructor_throws_error_without_arguments()
    {
        $dropbox = new Dropbox();
    }

    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function constructor_throws_error_with_only_one_argument()
    {
        $dropbox = new Dropbox('foo');
    }

    /**
     * @test
     */
    public function remote_returns_filesystem_instance()
    {
        $dropbox = $this->dropboxInstance();
        $this->assertInstanceOf(FilesystemInterface::class, $dropbox->remote);
    }
}