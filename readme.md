# Kirby log plugin

A little log utility you can use with Kirby 3.
It's a wrapper around [KLogger](https://github.com/katzgrau/KLogger).

⚠️ This plugin is currently a playground for me to test the new Kirby plugin system. Do not use in production _yet_. ⚠️ 

## Installation

Put the `kirby-log` folder in your `site/plugins` folder.
Run `composer install` from this directory.

## Usage

You can log to multiple logfiles, so create an instance with the name of the log you want to write to:

```

$myLogger = new bvdputte\kirbyLog\Logger("mylog.log");

```

_The logfile will be created automatically when not existant._

Write to the log:

```

$myLogger->write("This event will be added to mylog.log");

```

The output in the log will be prepended with a timestamp:

```

[2018-08-06 13:08:37.729538] This event will be added to mylog.log

```
