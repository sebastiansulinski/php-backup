<?php namespace SSDTest;

use PHPUnit_Framework_TestCase;

use SSD\Backup\Remotes\Dropbox;
use SSD\Backup\Remotes\Ftp;

abstract class BaseCase extends PHPUnit_Framework_TestCase
{

    /**
     * Path to the assets directory.
     *
     * @var string
     */
    protected $assets = __DIR__ . DIRECTORY_SEPARATOR . 'assets';

    /**
     * Path to the working directory.
     *
     * @var string
     */
    protected $working = __DIR__ . DIRECTORY_SEPARATOR . 'working';

    /**
     * Invalid path.
     *
     * @var string
     */
    protected $invalid = 0;

    /**
     * Files to remove on tearDown
     *
     * @var array
     */
    protected $remove_files = [];


    /**
     * Absolute path to the 'terms.txt' file.
     *
     * @return string
     */
    protected function terms_file()
    {
        return $this->assets . DIRECTORY_SEPARATOR . 'terms.txt';
    }

    /**
     * Absolute path to the 'css' directory.
     *
     * @return string
     */
    protected function css_directory()
    {
        return $this->assets . DIRECTORY_SEPARATOR . 'css';
    }

    /**
     * Absolute path to the 'app.css' file inside 'css' directory.
     *
     * @return string
     */
    protected function css_file()
    {
        return $this->css_directory() . DIRECTORY_SEPARATOR . 'app.css';
    }

    /**
     * Absolute path to the 'css' directory.
     *
     * @return string
     */
    protected function css_components_directory()
    {
        return $this->css_directory() . DIRECTORY_SEPARATOR . 'components';
    }

    /**
     * Absolute path to the 'app.css' file inside 'css' directory.
     *
     * @return string
     */
    protected function css_components_file()
    {
        return $this->css_components_directory() . DIRECTORY_SEPARATOR . 'text.css';
    }

    /**
     * Archive path with file name.
     *
     * @param $name
     * @return string
     */
    protected function archive_path($name)
    {
        return $this->working . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * Add file to the removal array.
     *
     * @param $file
     * @return void
     */
    protected function add_file_to_remove($file)
    {
        $this->remove_files[] = $file;
    }

    /**
     * Remove any generated files.
     *
     * @return void
     */
    protected function tearDown()
    {
        if (empty($this->remove_files)) {
            return;
        }

        foreach($this->remove_files as $file) {

            if ( ! is_file($file)) {
                throw new \InvalidArgumentException("{$file} is not a file.");
            }

            unlink($file);

        }
    }

    /**
     * Get Dropbox object instance.
     *
     * @return Dropbox
     */
    protected function dropboxInstance()
    {
        return new Dropbox('abc', 'def');
    }

    /**
     * Get Ftp object instance.
     *
     * @return Ftp
     */
    protected function ftpInstance()
    {
        return new Ftp('abc', 'def', 'abc');
    }

}