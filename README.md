# Have your own tardis

This is a very tiny symfony (2&3) bundle that simplifies usage of [php-timecop](https://github.com/hnw/php-timecop). To use this extension you need a php server with configured `timecop.so` extension. In case of troubles with compilation I can provide some extra instructions.

## Warning!

**Using this extension same as [php-timecop](https://github.com/hnw/php-timecop) alone can be very dangerous especially on production environment. Use it wisely.**

## Installation

```bash
composer require kolemp/timecop-bundle
```

## Configuration

After installation add the bundle to `app/AppKernel.php`. By default the extension is disabled. To enable it add a section to `config.yml` for environments you want it to be enabled:

```php
kolemp_timecop:
  enabled: true
```

## Time sources

You can set the time by query parameter or the cookie. Both are named `fakeTime`. The value given must be compatibile with [relative date formats](http://php.net/manual/en/datetime.formats.relative.php).
Example url: *example.com?fakeTime=+3 days*

### Disabling time source

You can disable either of time sources in config. By default both are enabled:

```php
kolemp_timecop:
  queryParameter: true
  cookie: true
```
