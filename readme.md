# Kirby log plugin

A little log utility you can use with Kirby 3.
It's a wrapper around [KLogger](https://github.com/katzgrau/KLogger).


## Installation

- unzip [master.zip](https://github.com/bvdputte/kirby-log/archive/master.zip) as folder `site/plugins/kirby-log` or
- `git submodule add https://github.com/bvdputte/kirby-log.git site/plugins/kirby-log` or
- `composer require bvdputte/kirby-log`

## Usage

By default, all kirby system exceptions are logged to `site/logs/kirbylog.log`.  
(Can be disabled via `'bvdputte.kirbylog.exceptionlog' => false` in config.php.

## Logging API:

```php
$site->log("message", "level" /* optional */, "appendcontext" /* optional */);
```

### Default usage

```php
$site->log("This text will be added to the default log");
```

- Output: `[2018-08-06 17:26:50.376956] [info] This text will be added to the default log`.
- Logfile: `/site/logs/kirbylog.log`

💡 The logfile will be created automatically when not existant.

### Define the loglevel

As defined by [PSR-3](https://www.php-fig.org/psr/psr-3/#5-psrlogloglevel), you can pass the wanted loglevel as the second argument in the `->log()` method:

```php
$site->log("My message", "error");
```

- Output: `[2018-08-06 17:26:50.372955] [error] My message`
- Logfile: `/site/logs/kirbylog.log`

💡 By default the loglevel is `info`. [This can be set in the options](#kirby-configurable-options).

### Log variables to log

AppendContext can be interesting to include variables to your log.

```php
$arr = ["foo", "bar", "baz"];
$site->log("My message", "debug", $arr);
```

- Output: 
```
[2018-08-06 17:26:50.373625] [debug] My message
    0: 'foo'
    1: 'bar'
    2: 'baz'
```
- Logfile: `/site/logs/kirbylog.log`

## Customize the logger

API:

```php
$site->logger("logname", "options" /* optional */, "defaultlevel" /* optional */)->log("message", "level" /* optional */, "appendcontext" /* optional */);
```

### 1. Custom log name

```php
$site->logger("my-own-logfile.log")->log("This event will be added to my custom named logfile");
```

- Output: `[2018-08-06 17:26:50.376956] [info] This event will be added to my custom named logfile`.
- Logfile: `/site/logs/my-own-logfile.log`

### 2. Extended options for the logger

Several extended options are available:
- dateFormat: [use PHP syntax](http://php.net/manual/en/function.date.php)
- logFormat: [formatting options](https://github.com/katzgrau/KLogger#log-formatting)
- [appendContext](#3-appendcontext): Enable/Disable

Pass them as follows to the logger using an associative array:

```php
$options = [
    'dateFormat'     => 'Y-m-d G:i:s.u',
    'logFormat'      => false,
    'appendContext'  => true,
];
$site->logger("infolog.log", $options)->log("Info about something", "info");
```

More info on [KLogger docs](https://github.com/katzgrau/KLogger#additional-options).

## Kirby configurable options

1. The default location where logfiles will be saved is the Kirby default logs location: `/site/logs/`. You can change this locaction via the [custom folder setup](https://getkirby.com/docs/guide/configuration#custom-folder-setup) and then change the [logs root](https://getkirby.com/docs/reference/system/roots/logs).
2. The default logname is `kirbylog.log`. Change it in `config.php` via `'bvdputte.kirbylog.logname' => 'custom-logname.log'`.
3. The default loglevel is `info`. Change it in `config.php` via `'bvdputte.kirbylog.defaultloglevel' => 'debug'`. Be sure to [use a valid PSR-3 loglevel](#loglevel).


## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bvdputte/kirby-log/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
