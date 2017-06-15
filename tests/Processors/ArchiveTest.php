<?php

namespace SSDTest\Processors;

use ZipArchive;

use SSDTest\BaseCase;

use SSD\Backup\Jobs\Job;
use SSD\Backup\Jobs\File;

use SSD\Backup\Backup;
use SSD\Backup\Processors\Archive;

class ArchiveTest extends BaseCase
{
    /**
     * @test
     */
    public function creates_archive()
    {
        $backup = new Backup(
            $this->dropboxInstance(),
            $this->working()
        );
        $backup->addJob(new Job(
            new File(
                $this->termsFile(),
                $this->assets()
            )
        ));
        $backup->prepare();
        $backup->processFiles();

        $archive = new Archive($backup, new ZipArchive);
        $archive->execute();

        $this->addFileToRemove($backup->archivePath());

        $this->assertFileExists($backup->archivePath());
    }
}