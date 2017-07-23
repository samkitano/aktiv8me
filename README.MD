[![Build Status](https://travis-ci.org/samkitano/aktiv8me.svg?branch=master)](https://travis-ci.org/samkitano/aktiv8me)
# Aktiv8me

## Authentication System for [Laravel](https://laravel.com) 5.4.* with email user activation

This package adds an email verification, a.k.a. account activation feature to the built-in
[Laravel Authentication System](https://laravel.com/docs/5.4/authentication#authentication-quickstart).
Upon registration, users will automatically recieve an email containing an activation link, which will allow to
activate their registrations.

### Requirements
php >=5.6.4

Laravel 5.4.*

### Before Installation
A clean, freshly installed Laravel is not required, but recommended.

### Installation

1 - Require with composer
```bash
    composer require samkitano/aktiv8me
```
2 - Add Service Provider to providers array in ```config/app.php```

```php
    Kitano\Aktiv8me\Aktiv8meServiceProvider::class,
```
3 - Run aktiv8me
```bash
    php artisan make:aktiv8me
```

4 - Run migrations
```bash
    php artisan migrate
```
Done!

### After Installation
There is no need to publish any assets at all. However, it is always possible to publish
config and language files by running *php artisan vendor:publish*

IMPORTANT: Email settings must be configured in *config/mail.php* to suit each project requirements!

If for some reason a reinstall is needed, running *php artisan make:aktiv8me* again will
prompt the developer to choose either to skip, overwrite, or backup each one of the involved files.
This may come in handy to reset individual files to their defaults, and to play around/test the package.
Backed up files will be stored with a 3 digit numbered extension.

Notifications (email compositions) are located in *app/Notifications/Aktiv8me*.

Included translations:

- (en) English
- (es) Castellano
- (pt) Português

NOTE: Translations are provided ONLY for this package's resources. Validation errors are NOT translated.

If not a fresh Laravel installation, the generated routes may have to be re-arranged in the *routes/web.php* file.

### Usage
| Command                   | Action                 | Description                                                   |
| ------------------------- | ---------------------- | ------------------------------------------------------------- |
| php artisan make:auth     | Default installation   | Normal installation/reinstall. Files will be backed up.       |
| php artisan make:auth --s | Skip Backup files      | Do not backup files during installation/reinstall.            |
| php artisan make:auth --r | Remove Backed up files | Not an uninstall. Removes ALL backed up files.                |

### Configuration
The configuration file *config/Aktiv8me.php* contains sensible defaults, and sets up a few easy to config options:

1) Option to disable verification, or to allow a number of tokens per registered user
2) Option to send a welcome email after successful activation
3) Option to set a Token expiration time
4) Option to auto-resend a new token if the used one is expired
5) Option to auto-login after verification

More details available in the config file itself.

### Notes
All redirections are pointing to '/' or to the required Forms, when needed.
Thus, if a project requires a Dashboard or an Admin section, developers will have to set up their own
resources and redirections.

The default Views *layouts/app.blade.php* and *welcome.blade.php* have been modified in order to
support alerts for flashed messages, just to make it easier for Devs to test the package before starting
their own project workflow.

Unless otherwise specified (artisan make:aktiv8me --s), all of the original Laravel's default
files will be renamed with an '.original' extension, and kept on their original directories, for convenience.

The package keeps a list of all backed up files. Running *php artisan make:auth --r* will remove **ALL** of them.

Flashed messages and JSON responses are set by the package, and they contain adequate information for most
applications.

### Contribution
Is always welcome, in all forms: Suggestions, Bug reports, Security issues, Pull requests,
Translations, Documentation, Tests...

### License

Open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
