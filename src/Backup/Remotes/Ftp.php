<?php namespace SSD\Backup\Remotes;

use League\Flysystem\Adapter\Ftp as FtpAdapter;
use League\Flysystem\Filesystem;

class Ftp extends Remote
{
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
     * @param $host
     * @param $username
     * @param $password
     * @param array $other
     */
    public function __construct($host, $username, $password, array $other = [])
    {
        $this->config['host'] = $host;
        $this->config['username'] = $username;
        $this->config['password'] = $password;

        if ( ! empty($other)) {
            $this->config = array_replace($this->config, $other);
        }

        $this->remote = new Filesystem(new FtpAdapter($this->config));
    }
}