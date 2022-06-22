<?php

namespace SSDTest\Jobs;

use SSDTest\BaseCase;
use SSD\Backup\Jobs\PostgreSQLDatabase;

class PostgreSQLDatabaseTest extends BaseCase
{
    /**
     * @test
     */
    public function is_valid_returns_true_with_constructor_config()
    {
        $database = new PostgreSQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password'
        ]);

        $this->assertTrue($database->isValid());
    }

    /**
     * @test
     */
    public function is_valid_returns_true_with_method_populated_config()
    {
        $database = new PostgreSQLDatabase();
        $database->setHost('127.0.0.1')
                 ->setName('database_name')
                 ->setUser('database_user')
                 ->setPassword('database_password');

        $this->assertTrue($database->isValid());
    }

    /**
     * @test
     */
    public function is_valid_returns_true_with_property_assigned_config()
    {
        $database = new PostgreSQLDatabase();
        $database->host = '127.0.0.1';
        $database->name = 'database_name';
        $database->user = 'database_user';
        $database->password = 'database_password';

        $this->assertTrue($database->isValid());
    }

    /**
     * @test
     */
    public function is_valid_returns_false_with_missing_property_assignment()
    {
        $database = new PostgreSQLDatabase();

        $this->assertFalse($database->isValid());
    }

    /**
     * @test
     */
    public function file_name_equals_by_constructor_assignment()
    {
        $database = new PostgreSQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password',
            'fileName' => 'test_database'
        ]);

        $this->assertEquals(
            'test_database.sql',
            $database->fileName()
        );
    }

    /**
     * @test
     */
    public function file_name_equals_by_property_assignment()
    {
        $database = new PostgreSQLDatabase();
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

    /**
     * @test
     */
    public function file_name_equals_by_mutator()
    {
        $database = new PostgreSQLDatabase();
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

    /**
     * @test
     */
    public function default_file_name()
    {
        $database = new PostgreSQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password'
        ]);

        $this->assertStringStartsWith(
            $database->name . '_' . date('Y-m-d'),
            $database->fileName()
        );
    }

    /**
     * @test
     */
    public function data_type()
    {
        $database = new PostgreSQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password'
        ]);

        $this->assertEquals(
            'postgresql',
            $database->type()
        );
    }

    /**
     * @test
     */
    public function default_port()
    {
        $database = new PostgreSQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password'
        ]);

        $this->assertEquals(
            5432,
            $database->port
        );
    }

    /**
     * @test
     */
    public function custom_port_by_constructor_assignment()
    {
        $database = new PostgreSQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password',
            'port' => 555
        ]);

        $this->assertEquals(
            555,
            $database->port
        );
    }

    /**
     * @test
     */
    public function custom_port_by_property_assignment()
    {
        $database = new PostgreSQLDatabase();
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

    /**
     * @test
     */
    public function custom_port_by_mutator()
    {
        $database = new PostgreSQLDatabase();
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