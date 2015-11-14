<?php namespace SSDTest\Processors;

use PHPUnit_Framework_Error;

use SSDTest\BaseCase;
use SSD\Backup\Backup;
use SSD\Backup\Jobs\Job;
use SSD\Backup\Jobs\File as FileJob;
use SSD\Backup\Processors\File;

class FileTest extends BaseCase
{
    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function throws_error_without_both_valid_arguments()
    {
        $file = new File();
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

        $file = new File($backup);
    }

    /**
     * @test
     */
    public function adds_file_to_collection()
    {
        $backup = new Backup(
            $this->dropboxInstance(),
            $this->working
        );
        $backup->addJob(new Job(
            new FileJob(
                $this->css_file(),
                $this->assets
            ),
            'files'
        ));
        $backup->addJob(new Job(
            new FileJob(
                $this->terms_file(),
                $this->assets
            ),
            'files'
        ));
        $backup->prepare();
        $backup->processFiles();

        $this->assertCount(
            1,
            $backup->getCollection(),
            'Files are not in the collection'
        );

        $this->assertCount(
            2,
            $backup->getCollection()['files'],
            'There are more or less than 2 items in the "files" item of the collection'
        );
    }
}