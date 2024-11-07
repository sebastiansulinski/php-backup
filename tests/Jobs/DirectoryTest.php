<?php

namespace SSDTest\Jobs;

use PHPUnit\Framework\Attributes\Test;
use SSD\Backup\Jobs\Directory;
use SSDTest\BaseCase;

class DirectoryTest extends BaseCase
{
    #[Test]
    public function returns_css_directory_name_without_second_argument(): void
    {
        $directory = new Directory($this->cssDirectory());

        $this->assertEquals('css', $directory->asset());
    }

    #[Test]
    public function returns_component_directory_name_with_parent_directory_without_second_argument(): void
    {
        $directory = new Directory($this->cssComponentsDirectory());

        $this->assertEquals('components', $directory->asset());
    }

    #[Test]
    public function returns_css_directory_name_from_full_path(): void
    {
        $directory = new Directory(
            $this->cssDirectory(),
            $this->assets()
        );

        $this->assertEquals('css', $directory->asset());
    }

    #[Test]
    public function returns_component_directory_name_with_parent_directory(): void
    {
        $directory = new Directory(
            $this->cssComponentsDirectory(),
            $this->assets()
        );

        $this->assertEquals('css/components', $directory->asset());
    }

    #[Test]
    public function returns_component_directory_only(): void
    {
        $directory = new Directory(
            $this->cssComponentsDirectory(),
            $this->cssDirectory()
        );

        $this->assertEquals('components', $directory->asset());
    }

    #[Test]
    public function returns_null_for_same_full_and_root_path(): void
    {
        $directory = new Directory(
            $this->cssComponentsDirectory(),
            $this->cssComponentsDirectory()
        );

        $this->assertEmpty($directory->asset());
    }

    #[Test]
    public function empty_exclusions(): void
    {
        $directory = new Directory(
            $this->cssComponentsDirectory(),
            $this->cssComponentsDirectory()
        );

        $this->assertEmpty($directory->exclude);
    }

    #[Test]
    public function exclusions_not_empty(): void
    {
        $directory = new Directory(
            $this->cssDirectory(),
            $this->cssDirectory(),
            [
                $this->cssComponentsDirectory(),
            ]
        );

        $this->assertCount(1, $directory->exclude);
    }
}
