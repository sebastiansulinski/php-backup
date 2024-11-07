<?php

namespace SSDTest\Processors;

use PHPUnit\Framework\Attributes\Test;
use SSD\Backup\Backup;
use SSD\Backup\Jobs\File;
use SSD\Backup\Jobs\Job;
use SSD\Backup\Processors\Archive;
use SSDTest\BaseCase;
use ZipArchive;

class ArchiveTest extends BaseCase
{
    #[Test]
    public function creates_archive(): void
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
