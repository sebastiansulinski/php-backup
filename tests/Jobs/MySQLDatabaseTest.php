<?php

namespace SSDTest\Jobs;

use SSDTest\BaseCase;
use SSD\Backup\Jobs\MySQLDatabase;

class DatabaseTest extends BaseCase
{

    /**
     * @test
     */
    public function is_valid_returns_true_with_constructor_config()
    {
        $database = new MySQLDatabase([
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
        $database = new MySQLDatabase();
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
        $database = new MySQLDatabase();
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
        $database = new MySQLDatabase();

        $this->assertFalse($database->isValid());
    }

    /**
     * @test
     */
    public function file_name_equals_by_constructor_assignment()
    {
        $database = new MySQLDatabase([
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
        $database = new MySQLDatabase();
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
        $database = new MySQLDatabase();
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
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password'
        ]);

        $this->assertContains(
            $database->name . '_' . date('Y-m-d'),
            $database->fileName()
        );
    }

    /**
     * @test
     */
    public function data_type()
    {
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password'
        ]);

        $this->assertEquals(
            'mysql',
            $database->type()
        );
    }

    /**
     * @test
     */
    public function default_port()
    {
        $database = new MySQLDatabase([
            'host' => '127.0.0.1',
            'name' => 'database_name',
            'user' => 'database_user',
            'password' => 'database_password'
        ]);

        $this->assertEquals(
            3306,
            $database->port
        );
    }

    /**
     * @test
     */
    public function custom_port_by_constructor_assignment()
    {
        $database = new MySQLDatabase([
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
        $database = new MySQLDatabase();
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
        $database = new MySQLDatabase();
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