<?php

namespace SSD\Backup\Remotes;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as FtpAdapter;

class Ftp extends Remote
{
    /**
     * Configuration settings.
     *
     * @var array
     */
    private $config = [
        'port' => 21,
        'root' => '',
        'passive' => true,
        'ssl' => false,
        'timeout' => 30
    ];

    /**
     * Ftp constructor.
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param array $other
     */
    public function __construct(string $host, string $username, string $password, array $other = [])
    {
        $this->config['host'] = $host;
        $this->config['username'] = $username;
        $this->config['password'] = $password;

        if (!empty($other)) {
            $this->config = array_replace($this->config, $other);
        }

        $this->remote = new Filesystem(new FtpAdapter($this->config));
    }
}