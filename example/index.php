<?php

require "../vendor/autoload.php";


use SSD\DotEnv\DotEnv;

use SSD\Backup\Backup;

use SSD\Backup\Remotes\Dropbox;
use SSD\Backup\Remotes\Ftp;

use SSD\Backup\Jobs\Job;
use SSD\Backup\Jobs\Directory;
use SSD\Backup\Jobs\File;
use SSD\Backup\Jobs\MySQLDatabase;

$dotenv = new DotEnv([
    __DIR__ . '/.env'
]);
$dotenv->load();
$dotenv->required([
    'DROPBOX_SECRET',
    'DROPBOX_OAUTH',
    'FTP_HOST',
    'FTP_USER',
    'FTP_PASS',
    'DOMAIN_NAME',
    'DB_HOST',
    'DB_PORT',
    'DB_NAME',
    'DB_USER',
    'DB_PASS'
]);


// working directory
$workingDirectory = __DIR__ . '/tmp';

$remote = new Dropbox(
    getenv('DROPBOX_OAUTH'),
    getenv('DROPBOX_SECRET')
);


//$remote = new Ftp(
//    getenv('FTP_HOST'),
//    getenv('FTP_USER'),
//    getenv('FTP_PASS'),
//    [
//        'root' => 'public_html'
//    ]
//);


$backup = new Backup(
    $remote,
    $workingDirectory
);

// directory to which backup should be saved on the remote server
$backup->setRemoteDirectory(getenv('DOMAIN_NAME'));

// keep only 7 backups then overwrite the oldest one
$backup->setNumberOfBackups(7);

// add MySQL database to the backup
$backup->addJob(new Job(
    new MySQLDatabase([
        'host' => getenv('DB_HOST'),
        'name' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASS')
    ]),
    'database'
));

// add single file ot the backup
$backup->addJob(new Job(
    new File(
        __DIR__ . '/files/text.txt',
        __DIR__
    )
));

// add the entire directory to the backup
$backup->addJob(new Job(
    new Directory(
        __DIR__ . '/files/css',
        __DIR__ . '/files'
    )
));

// run backup
$backup->run();