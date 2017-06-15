<?php

namespace SSDTest;


use SSD\Backup\Remotes\Ftp;
use SSD\Backup\Remotes\Dropbox;

use PHPUnit\Framework\TestCase;

abstract class BaseCase extends TestCase
{
    /**
     * Invalid path.
     *
     * @var int
     */
    protected $invalid = 0;

    /**
     * Files to remove on tearDown
     *
     * @var array
     */
    protected $removeFiles = [];


    /**
     * Get assets path.
     *
     * @return string
     */
    protected function assets(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'assets';
    }

    /**
     * Get working path.
     *
     * @return string
     */
    protected function working(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'working';
    }

    /**
     * Absolute path to the 'terms.txt' file.
     *
     * @return string
     */
    protected function termsFile(): string
    {
        return $this->assets().DIRECTORY_SEPARATOR.'terms.txt';
    }

    /**
     * Absolute path to the 'css' directory.
     *
     * @return string
     */
    protected function cssDirectory(): string
    {
        return $this->assets().DIRECTORY_SEPARATOR.'css';
    }

    /**
     * Absolute path to the 'app.css' file inside 'css' directory.
     *
     * @return string
     */
    protected function cssFile(): string
    {
        return $this->cssDirectory().DIRECTORY_SEPARATOR.'app.css';
    }

    /**
     * Absolute path to the 'css' directory.
     *
     * @return string
     */
    protected function cssComponentsDirectory(): string
    {
        return $this->cssDirectory().DIRECTORY_SEPARATOR.'components';
    }

    /**
     * Absolute path to the 'app.css' file inside 'css' directory.
     *
     * @return string
     */
    protected function cssComponentsFile(): string
    {
        return $this->cssComponentsDirectory().DIRECTORY_SEPARATOR.'text.css';
    }

    /**
     * Archive path with file name.
     *
     * @param  string $name
     * @return string
     */
    protected function archivePath(string $name): string
    {
        return $this->working().DIRECTORY_SEPARATOR.$name;
    }

    /**
     * Add file to the removal array.
     *
     * @param  string $file
     * @return void
     */
    protected function addFileToRemove(string $file): void
    {
        $this->removeFiles[] = $file;
    }

    /**
     * Remove any generated files.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        if (empty($this->removeFiles)) {
            return;
        }

        foreach ($this->removeFiles as $file) {
            if (!is_file($file)) {
                continue;
            }
            unlink($file);
        }
    }

    /**
     * Get Dropbox object instance.
     *
     * @return \SSD\Backup\Remotes\Dropbox
     */
    protected function dropboxInstance(): Dropbox
    {
        return new Dropbox('abc');
    }

    /**
     * Get Ftp object instance.
     *
     * @return \SSD\Backup\Remotes\Ftp
     */
    protected function ftpInstance(): Ftp
    {
        return new Ftp('abc', 'def', 'abc');
    }
}