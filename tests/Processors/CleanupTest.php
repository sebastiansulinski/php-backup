<?php namespace SSDTest\Processors;

use TypeError;
use ZipArchive;

use SSDTest\BaseCase;

use SSD\Backup\Backup;
use SSD\Backup\Jobs\Job;
use SSD\Backup\Jobs\File;
use SSD\Backup\Processors\Archive;
use SSD\Backup\Processors\Cleanup;

class CleanupTest extends BaseCase
{
    /**
     * @test
     *
     * @expectedException TypeError
     */
    public function throws_error_without_valid_argument()
    {
        $cleanup = new Cleanup();
    }

    /**
     * @test
     */
    public function cleans_collection()
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

        $this->assertCount(
            1,
            $backup->getCollection(),
            'Collection does not contain 1 item'
        );

        $this->assertFileExists($backup->archivePath());

        $cleanup = new Cleanup($backup);
        $cleanup->execute();

        $this->assertEmpty(
            $backup->getCollection(),
            'Collection is not empty'
        );

        $this->assertFileNotExists($backup->archivePath());

    }
}