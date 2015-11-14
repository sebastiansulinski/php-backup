<?php namespace SSD\Backup\Remotes;

use Dropbox\Client;
use League\Flysystem\Dropbox\DropboxAdapter;
use League\Flysystem\Filesystem;

class Dropbox extends Remote
{
    /**
     * Dropbox constructor.
     *
     * @param $oauth
     * @param $secret
     */
    public function __construct($oauth, $secret)
    {
        $client = new Client($oauth, $secret);
        $this->remote = new Filesystem(new DropboxAdapter($client));
    }
}