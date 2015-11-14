<?php namespace SSDTest\Processors;

use PHPUnit_Framework_Error;
use ZipArchive;

use SSDTest\BaseCase;

use SSD\Backup\Jobs\File;
use SSD\Backup\Jobs\Job;

use SSD\Backup\Backup;
use SSD\Backup\Processors\Archive;

class ArchiveTest extends BaseCase
{
    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function throws_error_without_both_valid_arguments()
    {
        $archive = new Archive();
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

        $archive = new Archive($backup);
    }

    /**
     * @test
     */
    public function creates_archive()
    {
        $backup = new Backup(
            $this->dropboxInstance(),
            $this->working
        );
        $backup->addJob(new Job(
            new File(
                $this->terms_file(),
                $this->assets
            )
        ));
        $backup->prepare();
        $backup->processFiles();

        $archive = new Archive($backup, new ZipArchive);
        $archive->execute();

        $this->add_file_to_remove($backup->archivePath());

        $this->assertFileExists($backup->archivePath());
    }
}