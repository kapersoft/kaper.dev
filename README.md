# [kaper.dev](https://kaper.dev)

Personal profile site for [Jan Willem Kaper](https://github.com/kapersoft), built with Laravel.

## Customization

Profile content, links, and skill tags live in `config/profile.php`.

Replace `public/images/headshot.jpg` with your own photo.

## Development

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Locally, the site runs on [Laravel Herd](https://herd.laravel.com/) at [https://kaper.dev.test](https://kaper.dev.test).

## Testing

```bash
php artisan test
```

## Security

If you discover any security related issues, please email <kapersoft@gmail.com> instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.txt) for more information.
