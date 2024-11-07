<?php

namespace SSDTest\Jobs;

use PHPUnit\Framework\Attributes\Test;
use SSD\Backup\Jobs\File;
use SSDTest\BaseCase;

class FileTest extends BaseCase
{
    #[Test]
    public function returns_file_name_without_second_argument(): void
    {
        $file = new File($this->termsFile());

        $this->assertEquals('terms.txt', $file->asset());
    }

    #[Test]
    public function returns_file_name_from_full_path(): void
    {
        $file = new File(
            $this->termsFile(),
            $this->assets()
        );

        $this->assertEquals('terms.txt', $file->asset());
    }

    #[Test]
    public function returns_file_name_without_its_directory(): void
    {
        $file = new File($this->cssFile());

        $this->assertEquals('app.css', $file->asset());
    }

    #[Test]
    public function returns_file_name_with_its_directory(): void
    {
        $file = new File(
            $this->cssFile(),
            $this->assets()
        );

        $this->assertEquals('css/app.css', $file->asset());
    }

    #[Test]
    public function returns_component_file_name_with_its_directories(): void
    {
        $file = new File(
            $this->cssComponentsFile(),
            $this->assets()
        );

        $this->assertEquals('css/components/text.css', $file->asset());
    }

    #[Test]
    public function returns_component_file_name_with_only_component_directory(): void
    {
        $file = new File(
            $this->cssComponentsFile(),
            $this->cssDirectory()
        );

        $this->assertEquals('components/text.css', $file->asset());
    }

    #[Test]
    public function returns_full_path(): void
    {
        $file = new File($this->cssFile());

        $this->assertEquals($this->cssFile(), $file->getFullPath());
    }

    #[Test]
    public function returns_null_for_root_path_without_second_constructor_argument(): void
    {
        $file = new File($this->cssFile());

        $this->assertEmpty($file->getRootPath());
    }

    #[Test]
    public function returns_root_path_with_second_constructor_argument(): void
    {
        $file = new File(
            $this->cssFile(),
            $this->assets()
        );

        $this->assertEquals($this->assets(), $file->getRootPath());
    }
}
