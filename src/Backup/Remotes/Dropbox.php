<?php

namespace SSD\Backup\Remotes;

use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class Dropbox extends Remote
{
    /**
     * Dropbox constructor.
     */
    public function __construct(string $authorizationToken)
    {
        $client = new Client($authorizationToken);
        $this->remote = new Filesystem(new DropboxAdapter($client));
    }
}
