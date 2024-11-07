<?php

namespace SSDTest;

use PHPUnit\Framework\TestCase;
use SSD\Backup\Remotes\Dropbox;
use SSD\Backup\Remotes\Ftp;

abstract class BaseCase extends TestCase
{
    /**
     * Invalid path.
     */
    protected int $invalid = 0;

    /**
     * Files to remove on tearDown
     */
    protected array $removeFiles = [];

    /**
     * Get assets path.
     */
    protected function assets(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'assets';
    }

    /**
     * Get working path.
     */
    protected function working(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'working';
    }

    /**
     * Absolute path to the 'terms.txt' file.
     */
    protected function termsFile(): string
    {
        return $this->assets().DIRECTORY_SEPARATOR.'terms.txt';
    }

    /**
     * Absolute path to the 'css' directory.
     */
    protected function cssDirectory(): string
    {
        return $this->assets().DIRECTORY_SEPARATOR.'css';
    }

    /**
     * Absolute path to the 'app.css' file inside 'css' directory.
     */
    protected function cssFile(): string
    {
        return $this->cssDirectory().DIRECTORY_SEPARATOR.'app.css';
    }

    /**
     * Absolute path to the 'css' directory.
     */
    protected function cssComponentsDirectory(): string
    {
        return $this->cssDirectory().DIRECTORY_SEPARATOR.'components';
    }

    /**
     * Absolute path to the 'app.css' file inside 'css' directory.
     */
    protected function cssComponentsFile(): string
    {
        return $this->cssComponentsDirectory().DIRECTORY_SEPARATOR.'text.css';
    }

    /**
     * Archive path with file name.
     */
    protected function archivePath(string $name): string
    {
        return $this->working().DIRECTORY_SEPARATOR.$name;
    }

    /**
     * Add file to the removal array.
     */
    protected function addFileToRemove(string $file): void
    {
        $this->removeFiles[] = $file;
    }

    /**
     * Remove any generated files.
     */
    protected function tearDown(): void
    {
        if (empty($this->removeFiles)) {
            return;
        }

        foreach ($this->removeFiles as $file) {
            if (! is_file($file)) {
                continue;
            }
            unlink($file);
        }
    }

    /**
     * Get Dropbox object instance.
     */
    protected function dropboxInstance(): Dropbox
    {
        return new Dropbox('abc');
    }

    /**
     * Get Ftp object instance.
     */
    protected function ftpInstance(): Ftp
    {
        return new Ftp('abc', 'def', 'abc');
    }
}
