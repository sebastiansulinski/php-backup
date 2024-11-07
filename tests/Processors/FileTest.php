<?php

namespace SSDTest\Processors;

use PHPUnit\Framework\Attributes\Test;
use SSD\Backup\Backup;
use SSD\Backup\Jobs\File as FileJob;
use SSD\Backup\Jobs\Job;
use SSDTest\BaseCase;

class FileTest extends BaseCase
{
    #[Test]
    public function adds_file_to_collection(): void
    {
        $backup = new Backup(
            $this->dropboxInstance(),
            $this->working()
        );
        $backup->addJob(new Job(
            new FileJob(
                $this->cssFile(),
                $this->assets()
            ),
            'files'
        ));
        $backup->addJob(new Job(
            new FileJob(
                $this->termsFile(),
                $this->assets()
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
