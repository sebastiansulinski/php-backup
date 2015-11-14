<?php namespace SSD\Backup\Contracts;

interface Filesystem
{
    /**
     * Constructor
     *
     * @param $fullPath
     * @param null $rootPath
     */
    public function __construct($fullPath, $rootPath = null);

    /**
     * Directory or file name.
     *
     * @return mixed
     */
    public function asset();

    /**
     * Full path to the file or directory.
     *
     * @return mixed
     */
    public function fullPath();

    /**
     * Root path to the file or directory.
     *
     * @return mixed
     */
    public function rootPath();
}