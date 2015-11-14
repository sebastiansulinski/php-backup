<?php namespace SSDTest\Processors;

use PHPUnit_Framework_Error;

use SSDTest\BaseCase;
use SSD\Backup\Backup;
use SSD\Backup\Jobs\Job;
use SSD\Backup\Jobs\Directory as DirectoryJob;
use SSD\Backup\Processors\Directory;

class DirectoryTest extends BaseCase
{
    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function throws_error_without_both_valid_arguments()
    {
        $directory = new Directory();
    }

    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function throws_error_without_second_valid_arguments()
    {
        $backup = new Backup(
            $this->dropboxInstance(),
            $this->working
        );

        $directory = new Directory($backup);
    }

    /**
     * @test
     */
    public function adds_directory_to_collection()
    {
        $backup = new Backup(
            $this->dropboxInstance(),
            $this->working
        );
        $backup->addJob(new Job(
            new DirectoryJob(
                $this->css_directory(),
                $this->assets
            ),
            'directories'
        ));
        $backup->prepare();
        $backup->processDirectories();

        $this->assertCount(
            1,
            $backup->getCollection(),
            'Directory is not in the collection'
        );

        $this->assertCount(
            3,
            $backup->getCollection()['directories'],
            'There are more or less than 3 items in the "directories" item of the collection'
        );
    }
}