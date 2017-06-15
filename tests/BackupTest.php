<?php

namespace SSDTest;

use SSD\Backup\Backup;
use SSD\Backup\Jobs\Job;
use SSD\Backup\Jobs\File;
use SSD\Backup\Jobs\Directory;
use SSD\Backup\Remotes\Dropbox;
use SSD\Backup\Jobs\MySQLDatabase;
use SSD\Backup\Jobs\PostgreSQLDatabase;

class BackupTest extends BaseCase
{
    /**
     * @test
     */
    public function returns_remote_instance()
    {
        $backup = new Backup($this->dropboxInstance(), $this->working());

        $this->assertInstanceOf(Dropbox::class, $backup->remote);

    }

    /**
     * @test
     */
    public function returns_correct_local_directory()
    {
        $backup = new Backup($this->dropboxInstance(), $this->working());

        $this->assertEquals($this->working(), $backup->getLocalWorkingDirectory());

    }

    /**
     * @test
     */
    public function sets_and_returns_correct_remote_directory()
    {
        $backup = new Backup($this->dropboxInstance(), $this->working());
        $backup->setRemoteDirectory('test');

        $this->assertEquals('test', $backup->getRemoteDirectory());

    }

    /**
     * @test
     */
    public function sets_and_returns_correct_archive_name()
    {
        $backup = new Backup($this->dropboxInstance(), $this->working());
        $backup->setArchiveName('test_archive');

        $this->assertEquals('test_archive.zip', $backup->getArchiveName());

    }

    /**
     * @test
     */
    public function returns_correct_archive_path()
    {
        $backup = new Backup($this->dropboxInstance(), $this->working());
        $backup->setArchiveName('test_archive');

        $this->assertEquals($this->archivePath('test_archive.zip'), $backup->archivePath());

    }

    /**
     * @test
     */
    public function adds_jobs()
    {
        $backup = new Backup($this->dropboxInstance(), $this->working());
        $backup->addJob(new Job(
            new MySQLDatabase([
                'host' => 'foo',
                'name' => 'bar',
                'user' => 'abc',
                'password' => 'password'
            ]),
            'database'
        ));
        $backup->addJob(new Job(
            new PostgreSQLDatabase([
                'host' => 'foo',
                'name' => 'bar',
                'user' => 'abc',
                'password' => 'password'
            ]),
            'database'
        ));
        $backup->addJob(new Job(
            new File(
                $this->cssFile(),
                $this->cssDirectory()
            )
        ));
        $backup->addJob(new Job(
            new Directory(
                $this->cssComponentsDirectory(),
                $this->cssDirectory()
            )
        ));

        $jobs = $backup->getJobs();

        $this->assertEquals(
            4,
            count($jobs),
            'Number of jobs does not equal 4'
        );

        $this->assertEquals(
            'database',
            $jobs[0]->namespace,
            'MySQLDatabase job namespace is not set to "database"'
        );

        $this->assertInstanceOf(
            MySQLDatabase::class,
            $jobs[0]->job,
            'Job is not instance of MySQLDatabase'
        );

        $this->assertInstanceOf(
            PostgreSQLDatabase::class,
            $jobs[1]->job,
            'Job is not instance of PostgreSQLDatabase'
        );

        $this->assertEmpty(
            $jobs[2]->namespace,
            'File job namespace is not empty'
        );

        $this->assertInstanceOf(
            File::class,
            $jobs[2]->job,
            'Job is not instance of File'
        );

        $this->assertEmpty(
            $jobs[3]->namespace,
            'Directory job namespace is not empty'
        );

        $this->assertInstanceOf(
            Directory::class,
            $jobs[3]->job,
            'Job is not instance of Directory'
        );

    }

}