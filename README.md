# PHP Backup

A simple package for backing up mysql databases, files and directories to Dropbox and FTP.

This package makes use of

- [backup-manager/backup-manager](https://github.com/backup-manager/backup-manager)
- [thephpleague/flysystem](https://github.com/thephpleague/flysystem)
- [ZipArchive](http://php.net/manual/en/class.ziparchive.php)
- [briannesbitt/Carbon](https://github.com/briannesbitt/Carbon)

## Usage examples

You can watch this [video tutorial](https://ssdtutorials.com/courses/dropbox-backup) or read below.


### Backing up to Dropbox and sending Slack notifications along the way

*To send `slack` notifications, `composer require maknz/slack` and [obtain the webhook for your slack channel](https://my.slack.com/services/new/incoming-webhook)

```php
require "../vendor/autoload.php";

use SSD\DotEnv\DotEnv;
use SSD\Backup\Backup;
use SSD\Backup\Jobs\File;
use SSD\Backup\Jobs\Directory;
use SSD\Backup\Remotes\Dropbox;
use SSD\Backup\Jobs\MySQLDatabase;

use Carbon\Carbon
use Maknz\Slack\Client as SlackClient;
use Illuminate\Filesystem\Filesystem;

$dotenv = new DotEnv([__DIR__ . '/.env']);
$dotenv->load();
$dotenv->required([
    'DROPBOX_OAUTH',
    'REMOTE_DIR_NAME',
    'DB_HOST',
    'DB_PORT',
    'DB_NAME',
    'DB_USER',
    'DB_PASS'
]);

// working directory
$workingDirectory = __DIR__ . '/tmp';

// Slack client
$client = new SlackClient('https://hooks.slack.com/your_slack_webhook', [
    'username' => 'your_slack_username',
    'channel' => '#your_slack_channel',
    'link_names' => true
]);

$client->send('Project backup started at: ' . Carbon::now()->toDateTimeString());

try {

    $remote = new Dropbox(
        getenv('DROPBOX_OAUTH')
    );

    $backup = new Backup(
        $remote,
        $workingDirectory
    );

    // directory to which backup should be saved on the remote server
    $backup->setRemoteDirectory(getenv('REMOTE_DIR_NAME'));

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

    // add single file to the backup
    $backup->addJob(new Job(
        new File(
            __DIR__ . '/files/text.txt',
            __DIR__
        ),
        'files'
    ));

    // add the 'files' directory to the backup
    // but exclude the 'css' directory within
    $backup->addJob(new Job(
        new Directory(
            __DIR__ . '/files',
            __DIR__,
            [
                'files/css'
            ]
        ),
        'files'
    ));

    // run backup
    $backup->run();

} catch (Exception $exception) {

    $client->send('Project backup failed at: ' . Carbon::now()->toDateTimeString() .' with message: "'.$exception->getMessage().'"');

    $filesystem = new Filesystem;
    
    $filesystem->cleanDirectory($workingDirectory);

    $filesystem->prepend(
        $workingDirectory . DIRECTORY_SEPARATOR . 'error_log',
        $exception->getMessage() . PHP_EOL
    );

} finally {
 
    $client->send('Project backup finished at: ' . Carbon::now()->toDateTimeString());

}
```

### Backing up to Ftp

```php
require "../vendor/autoload.php";

use SSD\DotEnv\DotEnv;
use SSD\Backup\Backup;
use SSD\Backup\Jobs\File;
use SSD\Backup\Remotes\Ftp;
use SSD\Backup\Jobs\Directory;
use SSD\Backup\Jobs\MySQLDatabase;

use Illuminate\Filesystem\Filesystem;

try {

    $dotenv = new DotEnv([
        __DIR__ . '/.env'
    ]);
    $dotenv->load();
    $dotenv->required([
        'FTP_HOST',
        'FTP_USER',
        'FTP_PASS',
        'REMOTE_DIR_NAME',
        'DB_HOST',
        'DB_PORT',
        'DB_NAME',
        'DB_USER',
        'DB_PASS'
    ]);
    
    // working directory
    $workingDirectory = __DIR__ . '/tmp';

    $remote = new Ftp(
        getenv('FTP_HOST'),
        getenv('FTP_USER'),
        getenv('FTP_PASS')
    );

    $backup = new Backup(
        $remote,
        $workingDirectory
    );

    // directory to which backup should be saved on the remote server
    $backup->setRemoteDirectory(getenv('REMOTE_DIR_NAME'));

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

    // add single file to the backup
    $backup->addJob(new Job(
        new File(
            __DIR__ . '/files/text.txt',
            __DIR__
        ),
        'files'
    ));

    // add the entire directory to the backup
    $backup->addJob(new Job(
        new Directory(
            __DIR__ . '/files/css',
            __DIR__ . '/files'
        ),
        'files'
    ));

    // run backup
    $backup->run();

} catch (Exception $e) {

    $filesystem = new Filesystem;
    
    $filesystem->cleanDirectory($workingDirectory);

    $filesystem->prepend(
        $workingDirectory . DIRECTORY_SEPARATOR . 'error_log',
        $e->getMessage() . PHP_EOL
    );

}
```