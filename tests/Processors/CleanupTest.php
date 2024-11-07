<?php

namespace SSDTest\Processors;

use PHPUnit\Framework\Attributes\Test;
use SSD\Backup\Backup;
use SSD\Backup\Jobs\File;
use SSD\Backup\Jobs\Job;
use SSD\Backup\Processors\Archive;
use SSD\Backup\Processors\Cleanup;
use SSDTest\BaseCase;
use ZipArchive;

class CleanupTest extends BaseCase
{
    #[Test]
    public function cleans_collection(): void
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

        $this->assertFileDoesNotExist($backup->archivePath());

    }
}
