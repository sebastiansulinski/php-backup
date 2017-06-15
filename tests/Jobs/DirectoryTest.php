<?php

namespace SSDTest\Jobs;

use SSDTest\BaseCase;
use SSD\Backup\Jobs\Directory;

class DirectoryTest extends BaseCase
{
    /**
     * @test
     */
    public function returns_css_directory_name_without_second_argument()
    {
        $directory = new Directory($this->cssDirectory());

        $this->assertEquals('css', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_component_directory_name_with_parent_directory_without_second_argument()
    {
        $directory = new Directory($this->cssComponentsDirectory());

        $this->assertEquals('components', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_css_directory_name_from_full_path()
    {
        $directory = new Directory(
            $this->cssDirectory(),
            $this->assets()
        );

        $this->assertEquals('css', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_component_directory_name_with_parent_directory()
    {
        $directory = new Directory(
            $this->cssComponentsDirectory(),
            $this->assets()
        );

        $this->assertEquals('css/components', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_component_directory_only()
    {
        $directory = new Directory(
            $this->cssComponentsDirectory(),
            $this->cssDirectory()
        );

        $this->assertEquals('components', $directory->asset());
    }

    /**
     * @test
     */
    public function returns_null_for_same_full_and_root_path()
    {
        $directory = new Directory(
            $this->cssComponentsDirectory(),
            $this->cssComponentsDirectory()
        );

        $this->assertEmpty($directory->asset());
    }

    /**
     * @test
     */
    public function empty_exclusions()
    {
        $directory = new Directory(
            $this->cssComponentsDirectory(),
            $this->cssComponentsDirectory()
        );

        $this->assertEmpty($directory->exclude);
    }

    /**
     * @test
     */
    public function exclusions_not_empty()
    {
        $directory = new Directory(
            $this->cssDirectory(),
            $this->cssDirectory(),
            [
                $this->cssComponentsDirectory()
            ]
        );

        $this->assertCount(1, $directory->exclude);
    }

}