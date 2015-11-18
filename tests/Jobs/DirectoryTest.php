<?php namespace SSDTest\Jobs;

use InvalidArgumentException;
use PHPUnit_Framework_Error;

use SSDTest\BaseCase;
use SSD\Backup\Jobs\Directory;

class DirectoryTest extends BaseCase
{

    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function throws_error_for_constructor_without_arguments()
    {
        $file = new Directory();
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp " is not a valid directory."
     */
    public function throws_exception_for_non_directory_argument()
    {
        $directory = new Directory($this->terms_file());
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp " is not a valid directory."
     */
    public function throws_exception_for_invalid_root_directory_argument()
    {
        $directory = new Directory($this->terms_file(), $this->invalid);
    }

    /**
     * @test
     */
    public function returns_css_directory_name_without_second_argument()
    {
        $directory = new Directory($this->css_directory());

        $this->assertEquals('css', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_component_directory_name_with_parent_directory_without_second_argument()
    {
        $directory = new Directory($this->css_components_directory());

        $this->assertEquals('components', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_css_directory_name_from_full_path()
    {
        $directory = new Directory($this->css_directory(), $this->assets);

        $this->assertEquals('css', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_component_directory_name_with_parent_directory()
    {
        $directory = new Directory($this->css_components_directory(), $this->assets);

        $this->assertEquals('css/components', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_component_directory_only()
    {
        $directory = new Directory($this->css_components_directory(), $this->css_directory());

        $this->assertEquals('components', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_null_for_same_full_and_root_path()
    {
        $directory = new Directory($this->css_components_directory(), $this->css_components_directory());

        $this->assertFalse($directory->asset());
    }

    /**
     * @test
     */
    public function empty_exclusions()
    {
        $directory = new Directory(
            $this->css_components_directory(),
            $this->css_components_directory()
        );

        $this->assertEmpty($directory->exclude);
    }

    /**
     * @test
     */
    public function exclusions_not_empty()
    {
        $directory = new Directory(
            $this->css_directory(),
            $this->css_directory(),
            [
                $this->css_components_directory()
            ]
        );

        $this->assertCount(1, $directory->exclude);
    }

}