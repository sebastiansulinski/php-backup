<?php

namespace SSDTest\Processors;

use PHPUnit\Framework\Attributes\Test;
use SSD\Backup\Backup;
use SSD\Backup\Jobs\Directory as DirectoryJob;
use SSD\Backup\Jobs\Job;
use SSDTest\BaseCase;

class DirectoryTest extends BaseCase
{
    #[Test]
    public function adds_directory_to_collection(): void
    {
        $backup = new Backup(
            $this->dropboxInstance(),
            $this->working()
        );
        $backup->addJob(new Job(
            new DirectoryJob(
                $this->cssDirectory(),
                $this->assets()
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

    #[Test]
    public function adds_directory_to_collection_with_exclusion(): void
    {
        $backup = new Backup(
            $this->dropboxInstance(),
            $this->working()
        );
        $backup->addJob(new Job(
            new DirectoryJob(
                $this->cssDirectory(),
                $this->assets(),
                [
                    $this->cssComponentsDirectory(),
                ]
            ),
            'directories'
        ));
        $backup->prepare();
        $backup->processDirectories();

        $this->assertCount(
            1,
            $backup->getCollection()['directories'],
            'There is more then 1 item in the "directories" collection'
        );
    }
}
