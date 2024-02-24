# [kaper.dev](https://kaper.dev), a Pinkary proxy

This repository contains the backend of the <https://kaper.dev> website. The contents of the site are proxied from <https://pinkary.com/@kapersoft>.

[Pinkary](pinkary.com) is a linksite made by [Nuno Maduro](https://github.com/nunomaduro). See this [LinkedIn post](https://www.linkedin.com/posts/nunomaduro_pinkary-update-for-you-sort-your-links-by-activity-7165462165611659264-mU3J/) and [Twitter feed](https://twitter.com/enunomaduro/status/1759576002626261300) for more information.

## Using this repository

If you like to deploy your Pinkary linksite to your personal domain, feel free to use this repository

### Installation

- Clone this project.
- Run `composer install`.
- Copy `.env.example` to `.env`.
- Open `.env`-file in your editor, look for `PINKARY_USERNAME`, and fill in your Pinkary username.
- Deploy to your domain!

### More options

By default all requests to Pinkary.com are cached for one hour. If you like, you can change this value by adding the `PINKARY_CACHE_TTL`-key with the desised TTL in your `.env`-file.

All requests are proxied to <https://pinkary.com>. If, for some reason, the domain is changed, you can specify an alternative domain by addning the `PINKARY_BASE_URL`-ket with the new domain in your `.env`-file.

## Testing

Tests are defined in the `/tests`-folder. You can test this repo by running `php artisan test`.

## Security

If you discover any security related issues, please email <kapersoft@gmail.com> instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.txt) for more information.
