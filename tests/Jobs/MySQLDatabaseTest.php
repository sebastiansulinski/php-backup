<?php

namespace SSDTest\Jobs;

use PHPUnit\Framework\Attributes\Test;
use SSD\Backup\Jobs\MySQLDatabase;
use SSDTest\BaseCase;

class MySQLDatabaseTest extends BaseCase
{
    #[Test]
    public function is_valid_returns_true_with_constructor_config(): void
    {
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password',
        ]);

        $this->assertTrue($database->isValid());
    }

    #[Test]
    public function is_valid_returns_true_with_method_populated_config(): void
    {
        $database = new MySQLDatabase;
        $database->setHost('127.0.0.1')
            ->setName('database_name')
            ->setUser('database_user')
            ->setPassword('database_password');

        $this->assertTrue($database->isValid());
    }

    #[Test]
    public function is_valid_returns_true_with_property_assigned_config(): void
    {
        $database = new MySQLDatabase;
        $database->host = '127.0.0.1';
        $database->name = 'database_name';
        $database->user = 'database_user';
        $database->password = 'database_password';

        $this->assertTrue($database->isValid());
    }

    #[Test]
    public function is_valid_returns_false_with_missing_property_assignment(): void
    {
        $database = new MySQLDatabase;

        $this->assertFalse($database->isValid());
    }

    #[Test]
    public function file_name_equals_by_constructor_assignment(): void
    {
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password',
            'fileName' => 'test_database',
        ]);

        $this->assertEquals(
            'test_database.sql',
            $database->fileName()
        );
    }

    #[Test]
    public function file_name_equals_by_property_assignment(): void
    {
        $database = new MySQLDatabase;
        $database->host = '127.0.0.1';
        $database->name = 'database_name';
        $database->user = 'database_user';
        $database->password = 'database_password';
        $database->fileName = 'test_database';

        $this->assertEquals(
            'test_database.sql',
            $database->fileName()
        );
    }

    #[Test]
    public function file_name_equals_by_mutator(): void
    {
        $database = new MySQLDatabase;
        $database->setHost('127.0.0.1')
            ->setName('database_name')
            ->setUser('database_user')
            ->setPassword('database_password')
            ->setFileName('test_database');

        $this->assertEquals(
            'test_database.sql',
            $database->fileName()
        );
    }

    #[Test]
    public function default_file_name(): void
    {
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password',
        ]);

        $this->assertStringStartsWith(
            $database->name.'_'.date('Y-m-d'),
            $database->fileName()
        );
    }

    #[Test]
    public function data_type(): void
    {
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password',
        ]);

        $this->assertEquals(
            'mysql',
            $database->type()
        );
    }

    #[Test]
    public function default_port(): void
    {
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password',
        ]);

        $this->assertEquals(
            3306,
            $database->port
        );
    }

    #[Test]
    public function custom_port_by_constructor_assignment(): void
    {
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password',
            'port' => 555,
        ]);

        $this->assertEquals(
            555,
            $database->port
        );
    }

    #[Test]
    public function custom_port_by_property_assignment(): void
    {
        $database = new MySQLDatabase;
        $database->host = '127.0.0.1';
        $database->name = 'database_name';
        $database->user = 'database_user';
        $database->password = 'database_password';
        $database->port = 555;

        $this->assertEquals(
            555,
            $database->port
        );
    }

    #[Test]
    public function custom_port_by_mutator(): void
    {
        $database = new MySQLDatabase;
        $database->setHost('127.0.0.1')
            ->setName('database_name')
            ->setUser('database_user')
            ->setPassword('database_password')
            ->setFileName('test_database')
            ->setPort(555);

        $this->assertEquals(
            555,
            $database->port
        );
    }
}
