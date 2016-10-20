# Have your own tardis

This is a very tiny symfony (2&3) bundle that simplifies usage of [php-timecop](https://github.com/hnw/php-timecop). To use this extension you need a php server with configured `timecop.so` extension. In case of troubles with compilation I can provide some extra instructions.

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
