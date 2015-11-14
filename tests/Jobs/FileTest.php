<?php namespace SSDTest\Jobs;

use InvalidArgumentException;
use PHPUnit_Framework_Error;

use SSDTest\BaseCase;
use SSD\Backup\Jobs\File;

class FileTest extends BaseCase
{

    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function throws_error_for_constructor_without_arguments()
    {
        $file = new File();
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp " is not a valid file."
     */
    public function throws_exception_for_non_file_argument()
    {
        $file = new File($this->assets);
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp " is not a valid directory."
     */
    public function throws_exception_for_invalid_root_directory_argument()
    {
        $file = new File($this->terms_file(), $this->invalid);
    }

    /**
     * @test
     */
    public function returns_file_name_without_second_argument()
    {
        $file = new File($this->terms_file());

        $this->assertEquals('terms.txt', $file->asset());
    }

    /**
     * @test
     */
    public function returns_file_name_from_full_path()
    {
        $file = new File($this->terms_file(), $this->assets);

        $this->assertEquals('terms.txt', $file->asset());
    }

    /**
     * @test
     */
    public function returns_file_name_without_its_directory()
    {
        $file = new File($this->css_file());

        $this->assertEquals('app.css', $file->asset());
    }

    /**
     * @test
     */
    public function returns_file_name_with_its_directory()
    {
        $file = new File($this->css_file(), $this->assets);

        $this->assertEquals('css/app.css', $file->asset());
    }

    /**
     * @test
     */
    public function returns_component_file_name_with_its_directories()
    {
        $file = new File($this->css_components_file(), $this->assets);

        $this->assertEquals('css/components/text.css', $file->asset());
    }

    /**
     * @test
     */
    public function returns_component_file_name_with_only_component_directory()
    {
        $file = new File($this->css_components_file(), $this->css_directory());

        $this->assertEquals('components/text.css', $file->asset());
    }

    /**
     * @test
     */
    public function returns_full_path()
    {
        $file = new File($this->css_file());

        $this->assertEquals($this->css_file(), $file->fullPath());
    }

    /**
     * @test
     */
    public function returns_null_for_root_path_without_second_constructor_argument()
    {
        $file = new File($this->css_file());

        $this->assertNull($file->rootPath());
    }

    /**
     * @test
     */
    public function returns_root_path_with_second_constructor_argument()
    {
        $file = new File($this->css_file(), $this->assets);

        $this->assertEquals($this->assets, $file->rootPath());
    }

}