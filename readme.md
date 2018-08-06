# Kirby log plugin

A little log utility you can use with Kirby 3.
It's a wrapper around [KLogger](https://github.com/katzgrau/KLogger).

⚠️ This plugin is currently a playground for me to test the new Kirby plugin system. Do not use in production _yet_. ⚠️ 

## Installation

Put the `kirby-log` folder in your `site/plugins` folder.
Run `composer install` from this directory.

## Usage

### Simplest form

```

kirbylog()->log("This text will be added to the default log");

```

Output in `/site/kirbylogs/kirbylog.log` will be `[2018-08-06 17:26:50.376956] This text will be added to the default log`.

_FYI: The logfile will be created automatically when not existant._

### Custom log name

```

$kirbyLogger = kirbyLog("my-own-logfile.log");
$kirbyLogger->log("This event will be added to my custom named logfile");

```

Output in `/site/kirbylogs/my-own-logfile.log` will be `[2018-08-06 17:26:50.376956] This event will be added to my custom named logfile`.

### Loglevels

[As defined by PSR-3](https://www.php-fig.org/psr/psr-3/#5-psrlogloglevel):

```

kirbyLog("kirbylog.log", ['logFormat' => "[{date}] [{level}] {message}"])->log("test", "info");

```

Output in `/site/kirbylogs/kirbylog.log` will be `[2018-08-06 17:26:50.372955] [info] test`.


Be sure to use a custom logformat when you want to loglevels to be logged. (They are not logged by default).

### Advanced options

You can also set [the dateFormat](http://php.net/manual/en/function.date.php), [logFormat](https://github.com/katzgrau/KLogger#log-formatting) and appendContext when setting up the KirbyLog object:

```

$options = array (
    'dateFormat'     => 'Y-m-d G:i:s.u',
    'logFormat'      => false,
    'appendContext'  => true,
);
kirbyLog("infolog.log", $options)->log("Info about something", "info");

```

More info on [KLogger docs](https://github.com/katzgrau/KLogger#additional-options).

## Kirby configurable options

1. The default location where logfiles will be saved is `/site/kirbylogs/`. You can change `kirbylogs` foldername by using setting it via the options `$kirby->option("bvdputte.kirbylog.logfolder", "myownfoldername");`.
2. The default logname is `kirbylog.log`. Change it with `$kirby->option("bvdputte.kirbylog.logname", "custom-logname.log");`.
3. The default logformate is `[{date}] {message}` (thus _without the log level_). Change it with `$kirby->option("bvdputte.kirbylog.logformat", "[{date}] [{level}] {message}");`.
