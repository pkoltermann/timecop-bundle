# Have your own tardis

## Installation

```bash
composer require kolemp/timecop-bundle
```

## Configuration

After installation add the bundle to `app/AppKernel.php` and add a section to `app/config/config.yml` with allowed environments, for example:

```php
kolemp_timecop:
  allowed_environments: ['dev', 'staging']
```
