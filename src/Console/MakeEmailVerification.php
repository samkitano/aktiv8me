<?php

namespace Kitano\Aktiv8me\Console;

const AK_BACK = __DIR__.'/aktiv8me.backups.log';
const AK_STUB = __DIR__.'/stubs/make/';
const AK_FILE = __DIR__.'/files/make/';
const AK_AUTH = 'Http/Controllers/Auth/';
const AK_PIPE = ' | ';
const AK_LOG = __DIR__.'/aktiv8me.log';
const AK_EXT = '.original';

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class MakeEmailVerification extends Command
{
    use DetectsApplicationNamespace;

    /**
     * Will carry the assets to process
     *
     * @var array
     */
    protected $assets;

    /**
     * Will carry any performed backup
     *
     * @var array
     */
    protected $backups = [];

    /**
     * The file to store backed up files
     *
     * @var string
     */
    protected $backupsFile = AK_BACK;

    /**
     * Will carry check errors
     *
     * @var array
     */
    protected $checkErrors = [];

    /**
     * The console command description.
     *
     * Overrides property in \Illuminate\Console\Command
     *
     * @var string
     */
    protected $description = 'Scaffold basic login and registration views and routes '.
                             'with email verification for newly registered users';

    /**
     * Will carry directories to create
     *
     * @var array
     */
    protected $dirs = [];
    
    /**
     * Will indicate if there are previous package installations
     * If so, user will be prompted to confirm overwrites
     *
     * @var boolean
     */
    protected $isFirstInstall;

    /**
     * Logs Installation process
     *
     * @var string
     */
    protected $log;

    /**
     * The log file's location and name
     *
     * @see helpers.php
     *
     * @var string
     */
    protected $logFile = AK_LOG;

    /**
     * Will carry the application namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * The name and signature of the console command.
     *
     * Overrides property in \Illuminate\Console\Command
     *
     * @var string
     */
    protected $signature = 'make:aktiv8me
                    {--s : Skip backup files}
                    {--r : Remove ALL backup files}';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->setConsoleStyles();

        if ($this->option('r')) {
            $this->setUpBackupRemoval()
                 ->removeBackups()
                 ->finish();
        } else {
            $this->setUpInstall()
                 ->install()
                 ->finish();
        }
    }

    /**
     * Add an entry to the log file
     *
     * @param string    $entry
     * @param bool|true $nodate
     */
    protected function add2Log($entry, $nodate = true)
    {
        $content = $nodate ? PHP_EOL.$entry : PHP_EOL.Carbon::now()->toDateTimeString().' | '.$entry;

        file_put_contents($this->logFile, $content, FILE_APPEND);
    }

    /**
     * Add a backup
     *
     * @param $file
     */
    protected function addBackup($file)
    {
        $this->backups[] = $file;
        $this->saveBackups();
    }

    /**
     * Ask for user choice on creating assets
     *
     * @param $question
     *
     * @return bool
     */
    protected function askPermission($question)
    {
        return $this->choice($question, ['Skip', 'Overwrite', 'Backup'], 0);
    }

    /**
     * Creates a numbered extension backup for a file
     *
     * @param $dest
     * @param $origin
     *
     * @return bool|int|mixed
     */
    protected function backupAsset($dest, $origin)
    {
        $fname = $this->numberExtension($dest);
        $this->addBackup($fname);

        return file_put_contents($fname, $origin);
    }

    /**
     * @param $group
     *
     * @return void
     */
    protected function checkBackups($group)
    {
        foreach ($group as $orig => $dest) {
            if (! file_exists($orig)) {
                $this->checkErrors[] = "File {$orig} is missing";
            }
        }
    }

    /**
     * @param array $group
     *
     * @return void
     */
    protected function checkCompile($group)
    {
        foreach ($group as $item) {
            foreach ($item as $orig => $dest) {
                $file = AK_STUB.$orig;

                if (! file_exists($file)) {
                    $this->checkErrors[] = "File {$file} is missing";
                }
            }
        }
    }

    /**
     * @param array $group
     *
     * @return void
     */
    protected function checkCopy($group)
    {
        foreach ($group as $item) {
            foreach ($item as $orig => $dest) {
                $file = AK_FILE.$orig;

                if (! file_exists($file)) {
                    $this->checkErrors[] = "File {$file} is missing";
                }
            }
        }
    }

    /**
     * Check existence of required files and functions
     *
     * @return void
     */
    protected function checkInstallIntegrity()
    {
        echo "Pre-install check... ";

        foreach ($this->assets as $asset => $group) {
            if ($asset === 'directories' || $asset === 'routes') {
                continue;
            }

            $func_name = 'check'.ucfirst($asset);

            $this->$func_name($group);
        }

        if (! file_exists(base_path('routes/web.php'))) {
            $this->checkErrors[] = "File routes/web.php is missing";
        }

        if (count($this->checkErrors)) {
            $this->error('INSTALLATION ABORTED');
            $this->line('');
            $this->add2Log('PRE-CHECK: INSTALLATION ABORTED');
            $this->add2Log('REASONS:');

            foreach ($this->checkErrors as $error) {
                $this->error($error);
                $this->add2Log($error);
            }

            $this->add2Log('EXIT'.PHP_EOL);
            exit();
        }

        $this->add2Log('PRE-CHECK: OK'.PHP_EOL);
        $this->info('OK.');
        $this->line('');
    }

    /**
     * Compile a stub and create file on destination
     *
     * @param $stub
     * @param $dest
     *
     * @return string
     */
    protected function compileAsset($stub, $dest)
    {
        if (! $this->isFirstInstall && file_exists($dest)) {
            $proceed = $this->askPermission("{$dest} Already exists.");

            switch ($proceed) {
                case 'Skip':
                    break;
                case 'Overwrite':
                    file_put_contents($dest, $this->compileStub($stub));
                    break;
                case 'Backup':
                    $this->backupAsset($dest, $this->compileStub($stub));
                    break;
                default:
                    break;
            }

            return $proceed;
        }

        file_put_contents($dest, $this->compileStub($stub));

        return 'Create';
    }

    /**
     * Compiles the given Stub
     *
     * @param string $stub
     *
     * @return mixed
     */
    protected function compileStub($stub)
    {
        return str_replace('{{namespace}}', $this->namespace, file_get_contents($stub));
    }

    /**
     * Copy an asset to it's destination
     *
     * @param $file
     * @param $dest
     *
     * @return string
     */
    protected function copyAsset($file, $dest)
    {
        if (! $this->isFirstInstall && file_exists($dest)) {
            $proceed = $this->askPermission("{$dest} Already exists.");

            switch ($proceed) {
                case 'Skip':
                    break;
                case 'Overwrite':
                    copy($file, $dest);
                    break;
                case 'Backup':
                    $fname = $this->numberExtension($dest);
                    $this->addBackup($fname);
                    copy($file, $this->numberExtension($fname));
                    break;
                default:
                    break;
            }

            return $proceed;
        }

        copy($file, $dest);

        return 'Create';
    }

    /**
     * Creates the backups file
     *
     * @return void
     */
    protected function createBackupsFile()
    {
        if (! file_exists($this->backupsFile)) {
            touch($this->backupsFile);
        }
    }

    /**
     * Creates the log file upon installation
     * or adds init install entry
     *
     * @return void
     */
    protected function createLog()
    {
        if (! file_exists($this->logFile)) {
            file_put_contents($this->logFile, 'FIRST INSTALL'.PHP_EOL);
        }

        $entry = 'DATE-TIME: '.Carbon::now()->toDateTimeString();
        $entry .= PHP_EOL;
        $entry .= 'Command: make:aktiv8me';
        $entry .= $this->option('s') ? ' --s | Skip Backup Files' : '';
        $entry .= $this->option('r') ? ' --s | Remove Backup Files' : '';

        $this->add2Log($entry);
    }

    /**
     * Finish Install
     *
     * @return void
     */
    protected function finish()
    {
        $this->add2Log('EXIT'.PHP_EOL);
        $this->line('');

        if ($this->option('r')) {
            $this->alert('Aktiv8me - ALL backups removed!');
        } else {
            $this->alert('Aktiv8me - Scaffolding Done!');
            $this->comment(' You can now run: php artisan migrate ');
        }

        $this->line('');
    }

    /**
     * Finish processing
     *
     * @param $th
     * @param $tr
     *
     * @return void
     */
    protected function finishProcess($th, $tr)
    {
        $this->add2Log('OK.');
        $this->info('OK.');
        $this->table($th, $tr);
        $this->line('');
    }

    /**
     * Create an array with existing routes from routes file
     *
     * @return array
     */
    protected function getExistingRoutes()
    {
        $routes_file = base_path('routes/web.php');
        $routes = explode("\n", file_get_contents($routes_file));
        $clean = [];
        $partial = '';

        foreach ($routes as $route) {
            $route = trim($route);
            $isEndOfStatement = substr($route, -1) === ';';

            if (substr($route, 0, 5) === 'Route' || substr($route, 0, 15) === 'Auth::routes();') {
                if ($isEndOfStatement) {
                    $clean[] = $route;
                    continue;
                }

                $partial .= $route;
            } else {
                if ($partial !== '' && $this->isValidRoute($route)) {
                    $partial .= $route;

                    if ($isEndOfStatement) {
                        $clean[] = $partial;
                        $partial = '';
                    }
                }
            }
        }

        return $clean;
    }

    /**
     * Proceed with installation
     *
     * @return $this
     */
    protected function install()
    {
        $this->checkInstallIntegrity();

        foreach ($this->assets as $asset => $group) {
            $func_name = 'process'.ucfirst($asset);
            $this->$func_name($group);
        }

        return $this;
    }

    /**
     * Filter the routes array for comments, blank lines and php tag
     *
     * @param $route
     *
     * @return bool
     */
    protected function isValidRoute($route)
    {
        if ($route === '') {
            return false;
        }

        $fst_char = substr($route, 0, 1);
        $invalid_chars = ['<', '|', '*', '/', '#',];

        return ! in_array($fst_char, $invalid_chars);
    }

    /**
     * Load saved backups links
     *
     * @return void
     */
    protected function loadBackups()
    {
        $content = file_get_contents($this->backupsFile);
        $this->backups = $content ? json_decode($content) : [];
    }

    /**
     * Adds a numbered extension to a File
     * MAX: 999
     *
     * @param string    $filename   Full path to file
     *
     * @return string
     */
    protected function numberExtension($filename)
    {
        $ext = '000';
        $f_name = $filename.'.'.$ext;
        $counter = 0;

        while (file_exists($f_name)) {
            $counter++;
            $ext = sprintf("%03d", $counter);
            $f_name = $filename.'.'.$ext;
        }

        return $f_name;
    }

    /**
     * Produce backups for original files
     *
     * @param array $files
     *
     * @return bool
     */
    protected function processBackups($files)
    {
        if ($this->option('s')) {
            $this->warn('Skipping backups!');
            $this->add2Log('BACKUPS SKIPPED');

            return false;
        }

        echo 'Creating Backups... ';
        $this->add2Log('Creating Backups...', false);

        $th = ['File', 'Backup', 'Status'];
        $tr = [];

        foreach ($files as $origin => $dest) {
            copy($origin, $dest);

            if (file_exists($dest)) {
                $tr[] = [$origin, $dest, 'OK'];
                $this->add2Log($origin.AK_PIPE.$dest.AK_PIPE.'OK');
                $this->addBackup($dest);
            } else {
                $tr[] = [$origin, $dest, 'FAIL'];
                $this->add2Log($origin.AK_PIPE.$dest.AK_PIPE.'FAIL');
            }
        }

        $this->finishProcess($th, $tr);

        return true;
    }

    /**
     * Produce compiled files
     *
     * @param array $group
     *
     * @return void
     */
    protected function processCompile($group)
    {
        echo 'Compiling stubs... ';
        $this->add2Log('Compiling stubs...', false);

        $th = ['Stub', 'Compiled', 'Status'];
        $tr = [];

        foreach ($group as $item) {
            foreach ($item as $orig => $dest) {
                $file = AK_STUB.$orig;
                $compiled = $this->compileAsset($file, $dest);
                $tr[] = [$file, $dest, $compiled];
                $this->add2Log($file.AK_PIPE.$dest.AK_PIPE.$compiled);
            }
        }

        $this->finishProcess($th, $tr);
    }

    /**
     * Produce Copied files
     *
     * @param array $group
     */
    protected function processCopy($group)
    {
        echo 'Publishing Files... ';
        $this->add2Log('Publishing Files...', false);

        $th = ['File', 'Published', 'Status'];
        $tr = [];

        foreach ($group as $item) {
            foreach ($item as $orig => $dest) {
                $file = AK_FILE.$orig;
                $copied = $this->copyAsset($file, $dest);
                $tr[] = [$file, $dest, $copied];
                $this->add2Log($file.AK_PIPE.$dest.AK_PIPE.$copied);
            }
        }

        $this->finishProcess($th, $tr);
    }

    /**
     * Create all necessary directories
     *
     * @param $group
     *
     * @return void
     */
    protected function processDirectories($group)
    {
        echo 'Creating Directories... ';
        $this->add2Log('Creating Directories...', false);

        $th = ['Directory', 'Status', 'Perms.'];
        $tr = [];

        foreach ($group as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);

                if (is_dir($dir)) {
                    $tr[] = [$dir, 'Created', substr(decoct(fileperms($dir)), 1)];
                    $this->add2Log($dir.AK_PIPE.'Created'.AK_PIPE.substr(decoct(fileperms($dir)), 1));
                    continue;
                }

                $tr[] = [$dir, 'Error', substr(decoct(fileperms($dir)), 1)];
                $this->add2Log($dir.AK_PIPE.'Error'.AK_PIPE.substr(decoct(fileperms($dir)), 1));
            } else {
                $tr[] = [$dir, 'Already Exists', substr(decoct(fileperms($dir)), 1)];
                $this->add2Log($dir.AK_PIPE.'Already Exists'.AK_PIPE.substr(decoct(fileperms($dir)), 1));
            }
        }

        $this->finishProcess($th, $tr);
    }

    /**
     * Include any new routes in routes/web.php
     *
     * @param $group
     */
    protected function processRoutes($group)
    {
        echo 'Merging Routes... ';
        $this->add2Log('Merging Routes...', false);

        $th = ['Route', 'Status'];
        $tr = [];
        $routes_array = $this->getExistingRoutes();
        $to_merge = '';
        $merge_count = 0;

        foreach ($group as $route => $str) {
            if (! in_array($str, $routes_array)) {
                $to_merge .= $str.PHP_EOL;
                $tr[] = [$str, 'Added'];
                $this->add2Log($str.AK_PIPE.'Added');
                $merge_count++;
                continue;
            }

            $tr[] = [$str, 'Already Present'];
            $this->add2Log($str.AK_PIPE.'Already Present');
        }

        $this->finishProcess($th, $tr);

        if ($merge_count) {
            file_put_contents(
                base_path('routes/web.php'),
                PHP_EOL.$to_merge,
                FILE_APPEND
            );

            if ($merge_count < 4) {
                $this->info('*** You may have to re-order the newly created routes.'.
                            ' `Auth::routes();` should be placed before others. ***');
            }
        }

        $this->line('');
    }

    /**
     * Remove all backups
     *
     * @return $this
     */
    protected function removeBackups()
    {
        if (! count($this->backups)) {
            $this->info('Nothing to remove');

            return $this;
        }

        $th = ['File', 'Status'];
        $tr = [];

        foreach ($this->backups as $backup) {
            unlink($backup);
            $this->add2Log('Removed'.$backup);
            $tr[] = [$backup, 'Removed'];
        }

        unlink($this->backupsFile);

        $this->createBackupsFile();
        $this->finishProcess($th, $tr);

        return $this;
    }

    /**
     * Save to backups file
     *
     * @return void
     */
    protected function saveBackups()
    {
        file_put_contents($this->backupsFile, json_encode($this->backups, JSON_PRETTY_PRINT));
    }

    /**
     * Set the console styles for output
     *
     * @return void
     */
    protected function setConsoleStyles()
    {
        $info = new OutputFormatterStyle('green');
        $comment = new OutputFormatterStyle('cyan');

        $this->output->getFormatter()->setStyle('info', $info);
        $this->output->getFormatter()->setStyle('comment', $comment);
    }

    /**
     * Start Install
     *
     * @return $this
     */
    protected function setUpInstall()
    {
        $this->isFirstInstall = ! file_exists($this->logFile);
        $this->namespace = rtrim($this->getAppNamespace(), '/\\');
        $this->createLog();
        $this->createBackupsFile();

        $back = [
            'welcome' => resource_path('views/welcome.blade.php'),
            'app' => AK_FILE.'views/layouts/app.blade.php',
            'auth_login' => resource_path('views/auth/login.blade.php'),
            'auth_register' => resource_path('views/auth/register.blade.php'),
            'web' => base_path('routes/web.php'),
            'user' => app_path('User.php'),
            'login' => app_path('Http/Controllers/Auth/LoginController.php'),
            'register' => app_path('Http/Controllers/Auth/RegisterController.php')
        ];

        // DO NOT CHANGE ARRAY ITEMS ORDER!
        $this->assets = [
            'directories' => [
                resource_path('lang/en'),
                resource_path('lang/es'),
                resource_path('lang/pt'),
                resource_path('views/layouts'),
                resource_path('views/auth/passwords'),
                app_path('Notifications/Aktiv8me'),
            ],

            'backups' => [
                // Views
                $back['welcome'] => $back['welcome'].AK_EXT,
                $back['app'] => resource_path('views/layouts/app.blade.php'.AK_EXT),
                $back['auth_login'] => resource_path('views/auth/login.blade.php'.AK_EXT),
                $back['auth_register'] => resource_path('views/auth/register.blade.php'.AK_EXT),
                // Routes
                $back['web'] => $back['web'].AK_EXT,
                // Models
                $back['user'] => $back['user'].AK_EXT,
                // Controllers
                $back['login'] => $back['login'].AK_EXT,
                $back['register'] => $back['register'].AK_EXT,
            ],

            'compile' => [
                'controllers' => [
                    'controllers/HomeController.stub' => app_path('Http/Controllers/HomeController.php'),
                    'controllers/RegisterController.stub' => app_path('Http/Controllers/Auth/RegisterController.php'),
                    'controllers/LoginController.stub' => app_path('Http/Controllers/Auth/LoginController.php'),
                ],
                'models' => [
                    'models/User.stub' => app_path('User.php'),
                    'models/RegistrationToken.stub' => app_path('RegistrationToken.php'),
                ],
                'notifications' => [
                    'notifications/AlreadyActive.stub' => app_path('Notifications/Aktiv8me/AlreadyActive.php'),
                    'notifications/ConfirmEmail.stub' => app_path('Notifications/Aktiv8me/ConfirmEmail.php'),
                    'notifications/EmailConfirmed.stub' => app_path('Notifications/Aktiv8me/EmailConfirmed.php'),
                    'notifications/TokenRenewed.stub' => app_path('Notifications/Aktiv8me/TokenRenewed.php'),
                ],
                'views' => [
                    'views/layouts/app.stub' => resource_path('views/layouts/app.blade.php'),
                ],
            ],

            'copy' => [
                'config' => [
                    'config/aktiv8me.php' => config_path('aktiv8me.php')
                ],
                'lang' => [
                    'lang/en/aktiv8me.php' => resource_path('lang/en/aktiv8me.php'),
                    'lang/pt/aktiv8me.php' => resource_path('lang/pt/aktiv8me.php'),
                    'lang/es/aktiv8me.php' => resource_path('lang/es/aktiv8me.php'),
                ],
                'views' => [
                    'views/auth/login.blade.php' => resource_path('views/auth/login.blade.php'),
                    'views/auth/register.blade.php' => resource_path('views/auth/register.blade.php'),
                    'views/auth/passwords/email.blade.php' => resource_path('views/auth/passwords/email.blade.php'),
                    'views/auth/passwords/reset.blade.php' => resource_path('views/auth/passwords/reset.blade.php'),
                    'views/home.blade.php' => resource_path('views/home.blade.php'),
                    'views/welcome.blade.php' => resource_path('views/welcome.blade.php'),
                ]
            ],

            'routes' => [
                'auth' => 'Auth::routes();',
                'home' => "Route::get('/home', 'HomeController@index')->name('home');",
                'verify' => "Route::get('/aktiv8me/verify/{token}',".
                            " '\\{$this->namespace}\\Http\\Controllers\\Auth\\RegisterController@verify')".
                            "->name('register.verify');",
                'get_resend' => "Route::get('/aktiv8me/resend',".
                                " '\\{$this->namespace}\\Http\\Controllers\\Auth\\RegisterController@resend');",
                'post_resend' => "Route::post('/aktiv8me/resend',".
                                 " '\\{$this->namespace}\\Http\\Controllers\\Auth\\RegisterController@resend')".
                                 "->name('register.resend');",
            ],
        ];

        $this->line('');
        $this->alert(' Aktiv8me - Scaffolding...  ');

        return $this;
    }

    /**
     * Prepare backup removal
     *
     * @return $this
     */
    protected function setUpBackupRemoval()
    {
        $this->alert(' Aktiv8me - Removig ALL backed up files...  ');

        if (! file_exists($this->backupsFile)) {
            $error = 'Can not remove backups! Backups list file not found.';
            $this->error($error);

            exit();
        }

        $this->loadBackups();
        $this->createLog();
        $this->add2Log('Loaded backups:');
        $this->add2Log(json_encode($this->backups, JSON_PRETTY_PRINT));

        return $this;
    }
}
