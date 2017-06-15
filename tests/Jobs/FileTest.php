<?php

namespace SSDTest\Jobs;

use SSDTest\BaseCase;
use SSD\Backup\Jobs\File;

class FileTest extends BaseCase
{
    /**
     * @test
     */
    public function returns_file_name_without_second_argument()
    {
        $file = new File($this->termsFile());

        $this->assertEquals('terms.txt', $file->asset());
    }

    /**
     * @test
     */
    public function returns_file_name_from_full_path()
    {
        $file = new File(
            $this->termsFile(),
            $this->assets()
        );

        $this->assertEquals('terms.txt', $file->asset());
    }

    /**
     * @test
     */
    public function returns_file_name_without_its_directory()
    {
        $file = new File($this->cssFile());

        $this->assertEquals('app.css', $file->asset());
    }

    /**
     * @test
     */
    public function returns_file_name_with_its_directory()
    {
        $file = new File(
            $this->cssFile(),
            $this->assets()
        );

        $this->assertEquals('css/app.css', $file->asset());
    }

    /**
     * @test
     */
    public function returns_component_file_name_with_its_directories()
    {
        $file = new File(
            $this->cssComponentsFile(),
            $this->assets()
        );

        $this->assertEquals('css/components/text.css', $file->asset());
    }

    /**
     * @test
     */
    public function returns_component_file_name_with_only_component_directory()
    {
        $file = new File(
            $this->cssComponentsFile(),
            $this->cssDirectory()
        );

        $this->assertEquals('components/text.css', $file->asset());
    }

    /**
     * @test
     */
    public function returns_full_path()
    {
        $file = new File($this->cssFile());

        $this->assertEquals($this->cssFile(), $file->getFullPath());
    }

    /**
     * @test
     */
    public function returns_null_for_root_path_without_second_constructor_argument()
    {
        $file = new File($this->cssFile());

        $this->assertEmpty($file->getRootPath());
    }

    /**
     * @test
     */
    public function returns_root_path_with_second_constructor_argument()
    {
        $file = new File(
            $this->cssFile(),
            $this->assets()
        );

        $this->assertEquals($this->assets(), $file->getRootPath());
    }

}